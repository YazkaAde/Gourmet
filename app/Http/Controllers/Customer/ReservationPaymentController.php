<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BankPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationPaymentController extends Controller
{
    public function create(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status === 'cancelled') {
            abort(403, 'Unauthorized or reservation cancelled');
        }

        $reservation->load(['table', 'orderItems.menu', 'payments']);

        $totalAmount = $reservation->total_amount;
        $totalPaid = $reservation->total_paid;
        $remainingBalance = $reservation->remaining_payment;

        $paymentCount = $reservation->payments()->count();
        if ($paymentCount === 0) {
            $paymentType = 'first';
            $minimumDownPayment = $reservation->down_payment_amount;
        } elseif ($paymentCount === 1 && $remainingBalance > 0) {
            $paymentType = 'second';
            $minimumDownPayment = 0;
        } else {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'Reservation payment is already completed or maximum payment limit reached.');
        }

        $bankTransferMethods = BankPaymentMethod::active()->bankTransfer()->get();
        $eWalletMethods = BankPaymentMethod::active()->eWallet()->get();

        return view('customer.reservation.payment', compact(
            'reservation', 
            'paymentType',
            'minimumDownPayment', 
            'totalAmount',
            'totalPaid',
            'remainingBalance',
            'bankTransferMethods',
            'eWalletMethods'
        ));
    }

    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status === 'cancelled') {
            abort(403);
        }

        $reservation->load(['table', 'orderItems.menu', 'payments']);

        $totalAmount = $reservation->total_amount;
        $totalPaid = $reservation->total_paid;
        $remainingBalance = $reservation->remaining_payment;
        $paymentCount = $reservation->payments()->count();

        if ($paymentCount >= 2) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'Maximum payment limit (2 payments) reached for this reservation.');
        }

        $paymentType = $request->payment_type;

        $validationRules = [
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet,qris',
            'payment_type' => 'required|in:first,second',
            'notes' => 'nullable|string|max:500'
        ];

        if ($paymentType === 'first') {
            $validationRules['amount_paid'] = [
                'required',
                'numeric',
                'min:' . $reservation->down_payment_amount,
                'max:' . $remainingBalance
            ];
            $validationRules['bank_method_id'] = 'required_if:payment_method,bank_transfer,e_wallet|exists:bank_payment_methods,id';
        } else {
            $validationRules['amount_paid'] = [
                'required',
                'numeric',
                'min:' . $remainingBalance,
                'max:' . $remainingBalance
            ];
            $validationRules['bank_method_id'] = 'required_if:payment_method,bank_transfer,e_wallet|exists:bank_payment_methods,id';
        }

        $validated = $request->validate($validationRules);

        if ($paymentType === 'first' && $validated['amount_paid'] < $reservation->down_payment_amount) {
            return back()->withErrors([
                'amount_paid' => 'Minimum down payment is 10% of total amount (Rp ' . 
                                number_format($reservation->down_payment_amount, 0) . ')'
            ])->withInput();
        }

        if ($paymentType === 'second' && $validated['amount_paid'] != $remainingBalance) {
            return back()->withErrors([
                'amount_paid' => 'Final payment must be exactly the remaining balance: Rp ' . 
                                number_format($remainingBalance, 0)
            ])->withInput();
        }

        $paymentData = [
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'amount' => $validated['amount_paid'],
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] === 'cash' ? 'paid' : 'pending',
            'notes' => $validated['notes'] ?? 'Payment ' . ($paymentCount + 1) . ' for reservation #' . $reservation->id,
        ];

        if (in_array($validated['payment_method'], ['bank_transfer', 'e_wallet'])) {
            $bankMethod = BankPaymentMethod::find($validated['bank_method_id']);
            if ($bankMethod) {
                $paymentData['bank_name'] = $bankMethod->bank_name;
                $paymentData['account_number'] = $bankMethod->account_number;
            }
        }

        if ($validated['payment_method'] === 'cash') {
            $paymentData['amount_paid'] = $validated['amount_paid'];
            $paymentData['change'] = 0;
        }

        $payment = Payment::create($paymentData);

        if ($validated['payment_method'] === 'cash') {
            $this->processPayment($reservation, $payment);
        }

        $successMessage = $this->getSuccessMessage($validated['payment_method'], $paymentType, $validated['amount_paid']);

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', $successMessage);
    }

    private function getSuccessMessage($paymentMethod, $paymentType, $amountPaid)
    {
        $baseMessage = 'Payment of Rp ' . number_format($amountPaid, 0) . ' processed successfully. ';
        
        if ($paymentType === 'first') {
            $baseMessage .= 'Down payment submitted. ';
        } else {
            $baseMessage .= 'Final payment submitted. ';
        }

        if ($paymentMethod === 'cash') {
            $baseMessage .= 'Reservation status updated.';
        } else {
            $baseMessage .= 'Waiting for payment confirmation.';
        }

        return $baseMessage;
    }

    private function processPayment(Reservation $reservation, Payment $payment)
    {
        $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
        $totalAmount = $reservation->total_amount;

        if ($reservation->status === 'pending' && $totalPaid >= $reservation->down_payment_amount) {
            $reservation->update(['status' => 'confirmed']);
            
            if (!$reservation->hasOrder()) {
                $this->createOrderFromReservation($reservation);
            }
        }

        if ($totalPaid >= $totalAmount) {
            $reservation->update(['status' => 'completed']);
            
            if ($reservation->orders()->exists()) {
                $reservation->orders()->update(['status' => 'completed']);
            }
        }
    }

    private function createOrderFromReservation(Reservation $reservation)
    {
        $menuTotal = $reservation->orderItems()->sum('total_price');
        
        $order = Order::create([
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'table_number' => $reservation->table_number,
            'total_price' => $menuTotal,
            'status' => 'pending'
        ]);
        
        OrderItem::where('reservation_id', $reservation->id)
                ->whereNull('order_id')
                ->update(['order_id' => $order->id]);
        
        return $order;
    }
}