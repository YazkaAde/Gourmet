<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment for Order') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="font-medium">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $order->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Price</p>
                            <p class="font-medium">Rp {{ number_format($order->total_price, 0) }}</p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-6">
                        <h4 class="text-md font-semibold mb-3">Order Items:</h4>
                        <div class="space-y-3">
                            @foreach($order->orderItems as $orderItem)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($orderItem->menu->image_url)
                                            <img src="{{ asset('storage/' . $orderItem->menu->image_url) }}" 
                                                alt="{{ $orderItem->menu->name }}"
                                                class="w-12 h-12 object-cover rounded mr-3"
                                                onerror="this.style.display='none'">
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $orderItem->menu->name }}</p>
                                            <p class="text-sm text-gray-600">Qty: {{ $orderItem->quantity }} × Rp {{ number_format($orderItem->menu->price, 0) }}</p>
                                        </div>
                                    </div>
                                    <p class="font-medium text-gray-900">
                                        Rp {{ number_format($orderItem->menu->price * $orderItem->quantity, 0) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <p class="text-lg font-semibold text-gray-900">Total Amount:</p>
                            <p class="text-2xl font-bold text-primary-600">Rp {{ number_format($order->total_price, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                    
                    <form action="{{ route('customer.orders.payment.store', $order) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Cash Method dengan informasi khusus -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 relative">
                                    <input type="radio" name="payment_method" value="cash" class="text-primary-600 focus:ring-primary-500" required>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Cash (Process at Counter)</span>
                                    <span class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                        Cashier Only
                                    </span>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Bank Transfer</span>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="debit_card" class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Debit Card</span>
                                </label>
                                
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="qris" class="text-primary-600 focus:ring-primary-500">
                                    <span class="ml-3 text-sm font-medium text-gray-700">QRIS</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="cashInfo" class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V5z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-yellow-700 text-sm">
                                    For cash payments, please proceed to the cashier counter. The cashier will process your payment and provide receipt.
                                </p>
                            </div>
                        </div>

                        <div id="nonCashInfo" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-blue-700 text-sm">
                                    Online payments will be verified by our cashier. Please complete the transaction and our staff will confirm your payment.
                                </p>
                            </div>
                        </div>

                        <!-- Notes Field -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Payment Notes (Optional)
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                placeholder="Add any payment reference or notes..."></textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customer.orders.show', $order) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Back to Order
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
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
    const cashInfo = document.getElementById('cashInfo');
    const nonCashInfo = document.getElementById('nonCashInfo');

    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            cashInfo.classList.add('hidden');
            nonCashInfo.classList.add('hidden');

            if (this.value === 'cash') {
                cashInfo.classList.remove('hidden');
            } else {
                nonCashInfo.classList.remove('hidden');
            }
        });
    });

    const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
    if (selectedMethod) {
        if (selectedMethod.value === 'cash') {
            cashInfo.classList.remove('hidden');
        } else {
            nonCashInfo.classList.remove('hidden');
        }
    }
});
</script>
</x-app-layout>