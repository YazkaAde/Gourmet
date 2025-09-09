<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationPaymentController extends Controller
{
    public function create(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id() || $reservation->status !== 'pending') {
            abort(403, 'Unauthorized or reservation not pending');
        }

        $table = $reservation->table;
        $reservationFee = $table->table_capacity * 10000;
        
        if ($table->table_capacity >= 8) {
            $reservationFee = $reservationFee * 0.8;
        }

        $menuTotal = $reservation->preOrderItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        $totalAmount = $reservationFee + $menuTotal;
        $minimumDownPayment = $totalAmount * 0.1;

        if ($reservation->payments()->where('status', '=', 'paid')->exists()) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('info', 'Payment already processed');
        }

        return view('customer.reservation.payment', compact('reservation', 'minimumDownPayment', 'totalAmount'));
    }

    public function store(Request $request, Reservation $reservation)
    {
    if ($reservation->user_id !== Auth::id() || $reservation->status !== 'pending') {
        abort(403);
    }

    $table = $reservation->table;
    $reservationFee = $table->table_capacity * 10000;
    
    if ($table->table_capacity >= 8) {
        $reservationFee = $reservationFee * 0.8;
    }

    $menuTotal = $reservation->preOrderItems->sum(function($item) {
        return $item->price * $item->quantity;
    });
    
    $totalAmount = $reservationFee + $menuTotal;

    if ($reservation->hasPaidPayment()) {
        return redirect()->route('customer.reservations.show', $reservation)
            ->with('info', 'Payment already processed');
    }
    
    $minimumDownPayment = $totalAmount * 0.1;
    $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
    $remainingBalance = $totalAmount - $totalPaid;

    $request->validate([
        'payment_method' => 'required|in:cash,credit_card,debit_card,qris,bank_transfer',
        'amount_paid' => [
            'required',
            'numeric',
            'min:' . max($minimumDownPayment, 1),
            'max:' . $remainingBalance
        ],
    ]);

    $paymentData = [
        'reservation_id' => $reservation->id,
        'user_id' => Auth::id(),
        'amount' => $request->amount_paid,
        'payment_method' => $request->payment_method,
        'status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
        'notes' => 'Down payment for reservation #' . $reservation->id,
    ];

    if ($request->payment_method === 'cash') {
        $paymentData['amount_paid'] = $request->amount_paid;
        $paymentData['change'] = $request->amount_paid - $request->amount_paid; 
    }

    $payment = Payment::create($paymentData);

    if ($payment->status === 'paid') {
        $reservation->update(['status' => 'confirmed']);
    }

    return redirect()->route('customer.reservations.show', $reservation)
        ->with('success', 'Payment processed successfully. Reservation ' . 
              ($payment->status === 'paid' ? 'confirmed' : 'pending payment confirmation'));
    }
}