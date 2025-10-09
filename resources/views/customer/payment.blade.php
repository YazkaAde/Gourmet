<!-- [file name]: payment.blade.php (MODIFIED) -->
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

            <!-- Order Information Section -->
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
                    
                    <form action="{{ route('customer.orders.payment.store', $order) }}" method="POST" id="paymentForm">
                        @csrf
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Cash Method -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 relative">
                                    <input type="radio" name="payment_method" value="cash" class="text-primary-600 focus:ring-primary-500 payment-method" required>
                                    <span class="ml-3 text-sm font-medium text-gray-700">Cash (Process at Counter)</span>
                                    <span class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                        Cashier Only
                                    </span>
                                </label>
                                
                                <!-- Bank Transfer -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="text-primary-600 focus:ring-primary-500 payment-method">
                                    <span class="ml-3 text-sm font-medium text-gray-700">Bank Transfer</span>
                                </label>
                                
                                <!-- E-Wallet -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="e_wallet" class="text-primary-600 focus:ring-primary-500 payment-method">
                                    <span class="ml-3 text-sm font-medium text-gray-700">E-Wallet</span> <!-- Updated -->
                                </label>
                                
                                <!-- QRIS -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="qris" class="text-primary-600 focus:ring-primary-500 payment-method">
                                    <span class="ml-3 text-sm font-medium text-gray-700">QRIS</span>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bankTransferFields" class="payment-fields hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-blue-800">Bank Transfer Details</h4>
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Bank Account</label>
                                <div class="grid gap-3">
                                    @foreach($bankTransferMethods as $bankMethod)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                                        <input type="radio" name="bank_method_id" value="{{ $bankMethod->id }}" 
                                               class="text-blue-600 focus:ring-blue-500 bank-method" 
                                               data-bank-name="{{ $bankMethod->bank_name }}"
                                               data-account-number="{{ $bankMethod->account_number }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $bankMethod->bank_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $bankMethod->account_number }}</p>
                                            <p class="text-xs text-gray-500">A/N: {{ $bankMethod->account_holder_name }}</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('bank_method_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                
                                <!-- Selected Account Display -->
                                <div id="selectedBankAccount" class="mt-4 p-3 bg-white border border-blue-300 rounded-lg hidden">
                                    <h5 class="font-medium text-blue-800 mb-2">Transfer to:</h5>
                                    <p class="text-sm" id="selectedBankName"></p>
                                    <p class="text-sm font-mono" id="selectedAccountNumber"></p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Please complete the transfer and keep the transaction receipt for verification.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- E-Wallet Fields -->
                        <div id="eWalletFields" class="payment-fields hidden mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h4 class="text-md font-semibold mb-3 text-green-800">E-Wallet Details</h4>
                            <div class="space-y-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select E-Wallet</label>
                                <div class="grid gap-3">
                                    @foreach($eWalletMethods as $eWalletMethod)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50">
                                        <input type="radio" name="bank_method_id" value="{{ $eWalletMethod->id }}" 
                                            class="text-green-600 focus:ring-green-500 ewallet-method" 
                                            data-wallet-name="{{ $eWalletMethod->bank_name }}" 
                                            data-account-number="{{ $eWalletMethod->account_number }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $eWalletMethod->bank_name }}</p> 
                                            <p class="text-sm text-gray-600">{{ $eWalletMethod->account_number }}</p>
                                            <p class="text-xs text-gray-500">A/N: {{ $eWalletMethod->account_holder_name }}</p>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('bank_method_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                
                                <!-- Selected E-Wallet Display -->
                                <div id="selectedEWallet" class="mt-4 p-3 bg-white border border-green-300 rounded-lg hidden">
                                    <h5 class="font-medium text-green-800 mb-2">Pay with:</h5>
                                    <p class="text-sm" id="selectedWalletName"></p>
                                    <p class="text-sm font-mono" id="selectedWalletNumber"></p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        Please complete the payment using the specified e-wallet details.
                                    </p>
                                </div>
                            </div>
                        </div>


                        <!-- Info Sections -->
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

                        <div id="qrisInfo" class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-purple-700 text-sm">
                                    For QRIS payments, please scan the QR code at the cashier counter and complete the payment. The cashier will verify your payment.
                                </p>
                            </div>
                        </div>

                        <div id="bankInfo" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-blue-700 text-sm">
                                    Please complete the bank transfer/debit card payment and our staff will verify your payment.
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
        const paymentMethods = document.querySelectorAll('.payment-method');
        const paymentFields = document.querySelectorAll('.payment-fields');
        const cashInfo = document.getElementById('cashInfo');
        const qrisInfo = document.getElementById('qrisInfo');
        const bankInfo = document.getElementById('bankInfo');
        
        function resetPaymentFields() {
            paymentFields.forEach(field => {
                field.classList.add('hidden');
                const inputs = field.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.removeAttribute('required');
                    if (input.type !== 'radio' && input.type !== 'checkbox') {
                        input.value = '';
                    }
                });
            });
            
            // Reset selected displays
            document.getElementById('selectedBankAccount').classList.add('hidden');
            document.getElementById('selectedEWallet').classList.add('hidden');
            
            // Reset info sections
            cashInfo.classList.add('hidden');
            qrisInfo.classList.add('hidden');
            bankInfo.classList.add('hidden');
        }

        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                resetPaymentFields();
                
                const methodValue = this.value;
                
                if (methodValue === 'cash') {
                    cashInfo.classList.remove('hidden');
                } else if (methodValue === 'qris') {
                    qrisInfo.classList.remove('hidden');
                } else if (methodValue === 'bank_transfer') {
                    bankInfo.classList.remove('hidden');
                    document.getElementById('bankTransferFields').classList.remove('hidden');
                    setRequiredFields(['bank_method_id']);
                } else if (methodValue === 'EWallet') {
                    bankInfo.classList.remove('hidden');
                    document.getElementById('EWalletFields').classList.remove('hidden');
                    setRequiredFields(['bank_method_id']);
                }
            });
        });

        // Bank method selection
        document.querySelectorAll('.bank-method').forEach(method => {
            method.addEventListener('change', function() {
                const bankName = this.getAttribute('data-bank-name');
                const accountNumber = this.getAttribute('data-account-number');
                
                document.getElementById('selectedBankName').textContent = bankName;
                document.getElementById('selectedAccountNumber').textContent = accountNumber;
                document.getElementById('selectedBankAccount').classList.remove('hidden');
            });
        });

        // E-Wallet method selection
        document.querySelectorAll('.ewallet-method').forEach(method => { 
            method.addEventListener('change', function() {
                const walletName = this.getAttribute('data-wallet-name');
                const accountNumber = this.getAttribute('data-account-number');
                
                document.getElementById('selectedWalletName').textContent = walletName;
                document.getElementById('selectedWalletNumber').textContent = accountNumber; 
                document.getElementById('selectedEWallet').classList.remove('hidden'); 
            });
        });

        function setRequiredFields(fieldNames) {
            fieldNames.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field) {
                    field.setAttribute('required', 'required');
                }
            });
        }

        // Form validation
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const selectedMethod = document.querySelector('.payment-method:checked');
            if (!selectedMethod) {
                e.preventDefault();
                alert('Please select a payment method');
                return;
            }

            const methodValue = selectedMethod.value;
            let isValid = true;

            if (methodValue === 'bank_transfer') {
            const bankMethod = document.querySelector('.bank-method:checked');
            if (!bankMethod) {
                isValid = false;
            }
            } else if (methodValue === 'e_wallet') {
                const ewalletMethod = document.querySelector('.ewallet-method:checked');
                if (!ewalletMethod) {
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please select a payment option for the selected payment method');
            }
        });
    });
    </script>
</x-app-layout>