<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Events\PaymentStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403, 'Unauthorized or order not completed');
        }

        if ($order->payment) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'Payment already processed');
        }

        $order->load(['orderItems.menu', 'payment']);

        return view('customer.payment', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,qris,bank_transfer',
        ]);

        $paymentData = [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->total_price,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash' ? 'pending' : 'paid',
        ];

        $payment = Payment::create($paymentData);

        if ($payment->status === 'paid') {
            event(new PaymentStatusUpdated($payment, 'pending', 'paid'));
            $order->update(['status' => 'completed']);
        }

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Payment processed successfully. ' . 
                ($request->payment_method === 'cash' ? 
                'Please wait for cashier to process your cash payment.' : 
                'Your payment has been confirmed.'));
    }
}