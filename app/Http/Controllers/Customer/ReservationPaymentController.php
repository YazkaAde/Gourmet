<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Order;
use App\Models\OrderItem;
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

        if ($totalPaid === 0) {
            $minimumDownPayment = $reservation->down_payment_amount;
        } else {
            $minimumDownPayment = 0;
        }

        return view('customer.reservation.payment', compact(
            'reservation', 
            'minimumDownPayment', 
            'totalAmount',
            'totalPaid',
            'remainingBalance'
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

        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,qris,bank_transfer',
            'amount_paid' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $remainingBalance
            ],
        ]);

        if ($totalPaid === 0 && $request->amount_paid < ($totalAmount * 0.1)) {
            return back()->withErrors([
                'amount_paid' => 'Minimum down payment is 10% of total amount (Rp ' . 
                                number_format($totalAmount * 0.1, 0) . ')'
            ])->withInput();
        }

        $paymentData = [
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount_paid,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
            'notes' => 'Payment for reservation #' . $reservation->id,
        ];

        if ($request->payment_method === 'cash') {
            $paymentData['amount_paid'] = $request->amount_paid;
            $paymentData['change'] = 0;
        }

        $payment = Payment::create($paymentData);

        if ($request->payment_method === 'cash') {
            $this->processPayment($reservation, $payment);
        }

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Payment processed successfully. ' . 
                ($payment->status === 'paid' ? 'Reservation confirmed.' : 'Waiting for payment confirmation.'));
    }

    private function processPayment(Reservation $reservation, Payment $payment)
    {
        $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
        $totalAmount = $reservation->total_amount;

        if ($reservation->status === 'pending' && $totalPaid >= ($totalAmount * 0.1)) {
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