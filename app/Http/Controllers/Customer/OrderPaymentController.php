<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderPaymentController extends Controller
{
    public function create(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($order->status !== 'completed') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Order is not ready for payment.');
        }

        $existingPayment = Payment::where('order_id', $order->id)
            ->where('status', 'paid')
            ->first();

        if ($existingPayment) {
            return redirect()->route('customer.orders.show', $order)
                ->with('info', 'Order has already been paid.');
        }

        return view('customer.payment', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,debit_card,qris',
            'notes' => 'nullable|string|max:255'
        ]);

        $status = ($validated['payment_method'] === 'cash') ? 'pending' : 'pending';

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->total_price,
            'payment_method' => $validated['payment_method'],
            'status' => $status,
            'notes' => $validated['notes'] ?? null
        ]);

        $successMessage = ($validated['payment_method'] === 'cash') 
            ? 'Cash payment selected. Please proceed to cashier counter for processing.'
            : 'Payment submitted successfully. Waiting for cashier verification.';

        return redirect()->route('customer.orders.show', $order)
            ->with('success', $successMessage);
    }
}