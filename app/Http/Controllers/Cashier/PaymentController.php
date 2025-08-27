<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $status = request('status', 'pending');
        
        $payments = Payment::with(['order.user', 'order.carts.menu'])
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cashier.payment', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.carts.menu', 'order.table']);

        return view('cashier.payments.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        $payment->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Payment confirmed successfully');
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load(['order.user', 'order.carts.menu', 'order.table']);

        return view('cashier.payments.receipt', compact('payment'));
    }

    public function processCashPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $payment->amount
        ]);

        $payment->update([
            'amount_paid' => $request->amount_paid,
            'change' => $request->amount_paid - $payment->amount,
            'status' => 'paid'
        ]);

        return redirect()->back()->with('success', 'Cash payment processed successfully');
    }
}