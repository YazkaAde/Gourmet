<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
        $remainingPayment = max($reservation->total_amount - $totalPaid, 0);
        $minimumPayment = $reservation->status === 'pending' ? $reservation->down_payment_amount : $remainingPayment;

        return view('customer.payment.create', compact('reservation', 'totalPaid', 'remainingPayment', 'minimumPayment'));
    }

    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,transfer,qris',
            'amount' => 'required|numeric|min:0.01',
            'amount_paid' => 'required_if:payment_method,cash|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
        $remainingPayment = max($reservation->total_amount - $totalPaid, 0);
        $minimumPayment = $reservation->status === 'pending' ? $reservation->down_payment_amount : $remainingPayment;

        if ($validated['amount'] < $minimumPayment) {
            return back()->withErrors(['amount' => 'Minimum payment amount is Rp ' . number_format($minimumPayment, 0)])->withInput();
        }

        if ($validated['payment_method'] === 'cash') {
            if ($validated['amount_paid'] < $validated['amount']) {
                return back()->withErrors(['amount_paid' => 'Amount paid must be greater than or equal to the payment amount'])->withInput();
            }
            
            $paymentData = [
                'reservation_id' => $reservation->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount'],
                'amount_paid' => $validated['amount_paid'],
                'change' => $validated['amount_paid'] - $validated['amount'],
                'status' => 'paid',
                'notes' => $validated['notes'] ?? null,
            ];
        } else {
            $paymentData = [
                'reservation_id' => $reservation->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount'],
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ];
        }

        $payment = Payment::create($paymentData);

        if ($payment->status === 'paid') {
            $this->updateReservationStatus($reservation);
        }

        return redirect()->route('customer.reservations.show', $reservation)
            ->with('success', 'Payment ' . ($payment->status === 'paid' ? 'completed' : 'pending') . ' successfully');
    }

    private function updateReservationStatus(Reservation $reservation)
    {
        $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
        
        if ($reservation->status === 'pending' && $totalPaid >= $reservation->down_payment_amount) {
            $reservation->update(['status' => 'confirmed']);
        }
        
        if ($totalPaid >= $reservation->total_amount) {
            $reservation->update(['status' => 'confirmed']);
        }
    }
}