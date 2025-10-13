<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\BankPaymentMethod;
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

        $bankTransferMethods = BankPaymentMethod::active()->bankTransfer()->get();
        $eWalletMethods = BankPaymentMethod::active()->eWallet()->get();

        return view('customer.payment', compact('order', 'bankTransferMethods', 'eWalletMethods'));
    }

    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:cash,bank_transfer,e_wallet,qris', // Pastikan e_wallet
            'bank_method_id' => 'required_if:payment_method,bank_transfer,e_wallet|exists:bank_payment_methods,id',
            'notes' => 'nullable|string|max:255'
        ]);

        $status = 'pending';
        $paymentData = [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $order->total_price,
            'payment_method' => $validated['payment_method'],
            'status' => $status,
            'notes' => $validated['notes'] ?? null
        ];

        if (in_array($validated['payment_method'], ['bank_transfer', 'e_wallet'])) {
            $bankMethod = BankPaymentMethod::find($validated['bank_method_id']);
            
            if ($bankMethod) {
                $paymentData['bank_name'] = $bankMethod->bank_name;
                $paymentData['account_number'] = $bankMethod->account_number;
            }
        }

        $payment = Payment::create($paymentData);

        $successMessage = $this->getSuccessMessage($validated['payment_method']);

        return redirect()->route('customer.orders.show', $order)
            ->with('success', $successMessage);
    }

    private function getSuccessMessage($paymentMethod)
    {
        $messages = [
            'cash' => 'Cash payment selected. Please proceed to cashier counter for processing.',
            'bank_transfer' => 'Bank transfer payment submitted. Please complete the transfer to the specified account.',
            'e_wallet' => 'E-Wallet payment submitted. Please complete the payment using the specified e-wallet details.',
            'qris' => 'QRIS payment selected. Please scan the QR code at the cashier counter and complete the payment.'
        ];

        return $messages[$paymentMethod] ?? 'Payment submitted successfully. Waiting for cashier verification.';
    }
}