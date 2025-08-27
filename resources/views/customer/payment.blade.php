<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment for Order #') }}{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Order Summary</h3>
                        <p class="text-gray-600">Total Amount: <span class="font-bold">Rp {{ number_format($order->total_price, 0) }}</span></p>
                        <p class="text-gray-600">Table: {{ $order->table_number }}</p>
                    </div>

                    <form action="{{ route('customer.orders.payment.store', $order) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="radio" id="cash" name="payment_method" value="cash" class="mr-2" checked>
                                    <label for="cash" class="text-gray-700">Cash</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="credit_card" name="payment_method" value="credit_card" class="mr-2">
                                    <label for="credit_card" class="text-gray-700">Credit Card</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="debit_card" name="payment_method" value="debit_card" class="mr-2">
                                    <label for="debit_card" class="text-gray-700">Debit Card</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="qris" name="payment_method" value="qris" class="mr-2">
                                    <label for="qris" class="text-gray-700">QRIS</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="mr-2">
                                    <label for="bank_transfer" class="text-gray-700">Bank Transfer</label>
                                </div>
                            </div>
                        </div>

                        <div id="cash-payment" class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                            <input type="number" name="amount_paid" 
                                   class="w-full p-2 border border-gray-300 rounded" 
                                   placeholder="Enter amount paid"
                                   min="{{ $order->total_price }}"
                                   required>
                            <p class="text-sm text-gray-500 mt-1">Minimum payment: Rp {{ number_format($order->total_price, 0) }}</p>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="{{ route('customer.orders.show', $order) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Back to Order
                            </a>
                            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                                Process Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const cashPaymentDiv = document.getElementById('cash-payment');
            
            function toggleCashPayment() {
                const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;
                cashPaymentDiv.style.display = selectedMethod === 'cash' ? 'block' : 'none';
                
                if (selectedMethod !== 'cash') {
                    document.querySelector('input[name="amount_paid"]').removeAttribute('required');
                } else {
                    document.querySelector('input[name="amount_paid"]').setAttribute('required', 'required');
                }
            }
            
            paymentMethods.forEach(method => {
                method.addEventListener('change', toggleCashPayment);
            });
            
            // Initial toggle
            toggleCashPayment();
        });
    </script>
</x-app-layout>