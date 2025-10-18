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
        $status = request('status');
        
        $payments = Payment::with([
                'order.user', 
                'order.orderItems.menu', 
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
                $query->with(['user', 'orderItems.menu', 'table']);
            },
            'reservation' => function($query) {
                $query->with(['user', 'table', 'preOrderItems.menu']);
            }
        ]);

        return view('cashier.payments.show', compact('payment'));
    }

    public function printReceipt(Payment $payment)
    {
        $payment->load([
            'order' => function($query) {
                $query->with(['user', 'orderItems.menu', 'table']);
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

    public function confirmPayment(Request $request, Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Payment is not pending confirmation.');
        }

        $oldStatus = $payment->status;
        
        if ($payment->payment_method === 'cash') {
            $request->validate([
                'amount_paid' => 'required|numeric|min:' . $payment->amount
            ]);

            $change = $request->amount_paid - $payment->amount;
            
            $payment->update([
                'amount_paid' => $request->amount_paid,
                'change' => max(0, $change),
                'status' => 'paid'
            ]);
        } else {
            $payment->update(['status' => 'paid']);
        }

        $this->updateRelatedStatus($payment);

        event(new PaymentStatusUpdated($payment, $oldStatus, 'paid'));

        $message = $payment->payment_method === 'cash' 
            ? 'Cash payment processed successfully. Change: Rp ' . number_format($payment->change, 0)
            : 'Payment confirmed successfully';

        return redirect()->route('cashier.payments.show', $payment)
            ->with('success', $message);
    }

    public function rejectPayment(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Payment is not pending.');
        }

        $oldStatus = $payment->status;
        $payment->update(['status' => 'failed']);

        event(new PaymentStatusUpdated($payment, $oldStatus, 'failed'));

        return redirect()->route('cashier.payments.show', $payment)
            ->with('success', 'Payment rejected successfully.');
    }

    private function updateRelatedStatus(Payment $payment)
    {
        if ($payment->order_id && $payment->order) {
            $payment->order->update(['status' => 'completed']);
        }

        if ($payment->reservation_id && $payment->reservation) {
            if ($payment->reservation->status === 'pending') {
                $payment->reservation->update(['status' => 'confirmed']);
            }
            
            if ($payment->reservation->shouldBeCompleted()) {
                $payment->reservation->update(['status' => 'completed']);
            }
        }
    }
}