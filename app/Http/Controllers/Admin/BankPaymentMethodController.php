<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankPaymentMethod;
use Illuminate\Http\Request;

class BankPaymentMethodController extends Controller
{
    public function index()
    {
        $bankMethods = BankPaymentMethod::orderBy('type')->orderBy('bank_name')->get();
        
        return view('admin.bankpaymentmethod', [
            'title' => 'Payment Methods Management',
            'bankMethods' => $bankMethods
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'type' => 'required|in:bank_transfer,e_wallet',
            'notes' => 'nullable|string|max:500'
        ]);

        BankPaymentMethod::create($validated);

        return redirect()->route('admin.bank-payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function update(Request $request, BankPaymentMethod $bankPaymentMethod)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder_name' => 'required|string|max:100',
            'type' => 'required|in:bank_transfer,e_wallet',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500'
        ]);

        $bankPaymentMethod->update($validated);

        return redirect()->route('admin.bank-payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(BankPaymentMethod $bankPaymentMethod)
    {
        $bankPaymentMethod->delete();

        return redirect()->route('admin.bank-payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    public function toggleStatus(BankPaymentMethod $bankPaymentMethod)
    {
        $bankPaymentMethod->update([
            'is_active' => !$bankPaymentMethod->is_active
        ]);

        $status = $bankPaymentMethod->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.bank-payment-methods.index')
            ->with('success', "Payment method {$status} successfully.");
    }
}