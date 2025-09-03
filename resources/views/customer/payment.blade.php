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
                            @foreach($order->carts as $cartItem)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($cartItem->menu->image_url)
                                            <img src="{{ asset('storage/' . $cartItem->menu->image_url) }}" 
                                                alt="{{ $cartItem->menu->name }}"
                                                class="w-12 h-12 object-cover rounded mr-3"
                                                onerror="this.style.display='none'">
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $cartItem->menu->name }}</p>
                                            <p class="text-sm text-gray-600">Qty: {{ $cartItem->quantity }} × Rp {{ number_format($cartItem->menu->price, 0) }}</p>
                                        </div>
                                    </div>
                                    <p class="font-medium text-gray-900">
                                        Rp {{ number_format($cartItem->menu->price * $cartItem->quantity, 0) }}
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
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" class="text-primary-600 focus:ring-primary-500" required>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Cash</span>
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

                        <!-- Cash Payment Fields (akan ditampilkan jika cash dipilih) -->
                        <div id="cashFields" class="mb-6 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                        Amount Paid
                                    </label>
                                    <input type="number" name="amount_paid" id="amount_paid" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                        placeholder="Enter amount paid"
                                        min="{{ $order->total_price }}">
                                    @error('amount_paid')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Change</label>
                                    <div class="p-2 bg-gray-100 rounded-md">
                                        <p class="text-gray-600" id="changeAmount">Rp 0</p>
                                    </div>
                                </div>
                            </div>
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
            const cashFields = document.getElementById('cashFields');
            const amountPaidInput = document.getElementById('amount_paid');
            const changeAmount = document.getElementById('changeAmount');
            const totalPrice = {{ $order->total_price }};

            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (this.value === 'cash') {
                        cashFields.classList.remove('hidden');
                        amountPaidInput.setAttribute('required', 'required');
                    } else {
                        cashFields.classList.add('hidden');
                        amountPaidInput.removeAttribute('required');
                        amountPaidInput.value = '';
                        changeAmount.textContent = 'Rp 0';
                    }
                });
            });

            amountPaidInput?.addEventListener('input', function() {
                const amountPaid = parseFloat(this.value) || 0;
                const change = amountPaid - totalPrice;
                changeAmount.textContent = `Rp ${change >= 0 ? change.toLocaleString() : '0'}`;
            });
        });
    </script>
</x-app-layout>