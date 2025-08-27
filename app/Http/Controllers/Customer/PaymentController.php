<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        // Authorization check
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403, 'Unauthorized or order not completed');
        }

        // Check if payment already exists
        if ($order->payment) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'Payment already processed');
        }

        return view('customer.payment', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        // Authorization check
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,qris,bank_transfer',
            'amount_paid' => $request->payment_method === 'cash' ? 'required|numeric|min:' . $order->total_price : 'nullable',
        ]);

        $paymentData = [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->total_price,
            'payment_method' => $request->payment_method,
            'status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
        ];

        if ($request->payment_method === 'cash') {
            $paymentData['amount_paid'] = $request->amount_paid;
            $paymentData['change'] = $request->amount_paid - $order->total_price;
        }

        Payment::create($paymentData);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Payment processed successfully');
    }
}