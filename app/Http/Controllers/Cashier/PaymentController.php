<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Events\PaymentStatusUpdated;
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

        return view('cashier.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load([
            'order' => function($query) {
                $query->with(['user', 'carts.menu', 'table']);
            }
        ]);

        if (!$payment->order) {
            return redirect()->route('cashier.payments.index')
                ->with('error', 'Associated order not found for this payment.');
        }

        return view('cashier.payments.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        if (!$payment->order) {
            return redirect()->back()
                ->with('error', 'Cannot confirm payment: associated order not found.');
        }

        $oldStatus = $payment->status;
        $payment->update(['status' => 'paid']);

        event(new PaymentStatusUpdated($payment, $oldStatus, 'paid'));

        return redirect()->route('cashier.payments.receipt', $payment)
            ->with('success', 'Payment confirmed successfully');
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load([
            'order' => function($query) {
                $query->with(['user', 'carts.menu', 'table']);
            }
        ]);

        if (!$payment->order) {
            return redirect()->route('cashier.payments.index')
                ->with('error', 'Cannot print receipt: associated order not found.');
        }

        return view('cashier.payments.receipt', compact('payment'));
    }

    public function processCashPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $payment->amount
        ]);

        if (!$payment->order) {
            return redirect()->back()
                ->with('error', 'Cannot process cash payment: associated order not found.');
        }

        $oldStatus = $payment->status;
        
        $payment->update([
            'amount_paid' => $request->amount_paid,
            'change' => $request->amount_paid - $payment->amount,
            'status' => 'paid'
        ]);

        event(new PaymentStatusUpdated($payment, $oldStatus, 'paid'));

        return redirect()->route('cashier.payments.receipt', $payment)
            ->with('success', 'Cash payment processed successfully');
    }
}