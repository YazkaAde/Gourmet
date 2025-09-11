<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Events\PaymentStatusUpdated;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
{
    $status = request('status', 'pending');
    
    $payments = Payment::with([
            'order.user', 
            'order.carts.menu', 
            'reservation.user',
            'reservation.table'
        ])
        ->when($status, function($query, $status) {
            return $query->where('status', $status);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('cashier.payments.index', compact('payments'));
}

public function show(Payment $payment)
{
    $payment->load([
        'order' => function($query) {
            $query->with(['user', 'carts.menu', 'table']);
        },
        'reservation' => function($query) {
            $query->with(['user', 'table', 'preOrderItems.menu']);
        }
    ]);

    return view('cashier.payments.show', compact('payment'));
}

    public function confirm(Payment $payment)
    {
        $payment->load(['order', 'reservation']);
        
        $oldStatus = $payment->status;
        $payment->update(['status' => 'paid']);

        if ($payment->order_id && $payment->order) {
            // Untuk order payment
            $payment->order->update(['status' => 'completed']);
        } else if ($payment->reservation_id && $payment->reservation) {
            if ($payment->reservation->status === 'pending') {
                $payment->reservation->update(['status' => 'confirmed']);
            }
        } else {
            return redirect()->back()
                ->with('error', 'Cannot confirm payment: no associated order or reservation found.');
        }

        event(new PaymentStatusUpdated($payment, $oldStatus, 'paid'));

        return redirect()->route('cashier.payments.show', $payment)
            ->with('success', 'Payment confirmed successfully');
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load([
            'order' => function($query) {
                $query->with(['user', 'carts.menu', 'table']);
            },
            'reservation' => function($query) {
                $query->with(['user', 'table', 'preOrderItems.menu']);
            }
        ]);

        if (($payment->order_id && (!$payment->order || !$payment->order->user)) || 
            ($payment->reservation_id && (!$payment->reservation || !$payment->reservation->user))) {
            return redirect()->route('cashier.payments.index')
                ->with('error', 'Cannot print receipt: customer data not found.');
        }

        return view('cashier.payments.receipt', compact('payment'));
    }

    public function processCashPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $payment->amount
        ]);

        $payment->load(['reservation']);
        
        $oldStatus = $payment->status;
        
        $payment->update([
            'amount_paid' => $request->amount_paid,
            'change' => $request->amount_paid - $payment->amount,
            'status' => 'paid'
        ]);

        if ($payment->reservation_id && $payment->reservation) {
            if ($payment->reservation->status === 'pending') {
                $payment->reservation->update(['status' => 'confirmed']);
            }
        }

        event(new PaymentStatusUpdated($payment, $oldStatus, 'paid'));

        return redirect()->route('cashier.payments.show', $payment)
            ->with('success', 'Cash payment processed successfully');
    }
}