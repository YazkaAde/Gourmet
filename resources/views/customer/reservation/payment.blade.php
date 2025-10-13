<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment for Reservation') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Reservation Information Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Reservation Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Reservation ID</p>
                            <p class="font-medium">#{{ $reservation->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $reservation->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Guest Count</p>
                            <p class="font-medium">{{ $reservation->guest_count }} guests</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reservation Date</p>
                            <p class="font-medium">{{ $reservation->reservation_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reservation Time</p>
                            <p class="font-medium">{{ $reservation->reservation_time }}</p>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-4">Payment Summary</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Reservation Fee</p>
                                <p class="font-medium">Rp {{ number_format($reservation->reservation_fee, 0) }}</p>
                            </div>
                            @if($reservation->orderItems->count() > 0)
                            <div>
                                <p class="text-sm text-gray-600">Menu Total</p>
                                <p class="font-medium">Rp {{ number_format($reservation->menu_total, 0) }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-medium text-lg">Rp {{ number_format($totalAmount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Paid</p>
                                <p class="font-medium">Rp {{ number_format($totalPaid, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Remaining Balance</p>
                                <p class="font-medium text-lg 
                                    @if($remainingBalance > 0) text-red-600 @else text-green-600 @endif">
                                    Rp {{ number_format($remainingBalance, 0) }}
                                </p>
                            </div>
                            @if($paymentType === 'first')
                            <div>
                                <p class="text-sm text-gray-600">Minimum Down Payment (10%)</p>
                                <p class="font-medium">Rp {{ number_format($minimumDownPayment, 0) }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Payment History -->
                        @if($reservation->payments->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-md font-semibold mb-3">Payment History</h4>
                            <div class="space-y-2">
                                @foreach($reservation->payments->sortByDesc('created_at') as $payment)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            Payment #{{ $loop->iteration }} - {{ ucfirst($payment->payment_method) }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $payment->created_at->format('M d, Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($payment->amount, 0) }}
                                        </p>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($payment->status == 'paid') bg-green-100 text-green-800
                                            @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">
                        @if($paymentType === 'first')
                            First Payment - Down Payment
                        @else
                            Final Payment - Settlement
                        @endif
                    </h3>
                    
                    <form action="{{ route('customer.reservations.payment.store', $reservation) }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="payment_type" value="{{ $paymentType }}">
                        
                        <!-- Payment Type Info -->
                        <div class="mb-6 p-4 
                            @if($paymentType === 'first') bg-blue-50 border border-blue-200
                            @else bg-green-50 border border-green-200 @endif rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 
                                    @if($paymentType === 'first') text-blue-600
                                    @else text-green-600 @endif mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="@if($paymentType === 'first') text-blue-700 @else text-green-700 @endif text-sm">
                                    @if($paymentType === 'first')
                                        This is your first payment. You can pay the minimum down payment (10%) or pay more. Minimum payment: <strong>Rp {{ number_format($minimumDownPayment, 0) }}</strong>
                                    @else
                                        This is your final payment to settle the remaining balance. The amount is fixed: <strong>Rp {{ number_format($remainingBalance, 0) }}</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
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
                                    <span class="ml-3 text-sm font-medium text-gray-700">E-Wallet</span>
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
                                    @forelse($bankTransferMethods as $bankMethod)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-blue-50">
                                        <input type="radio" name="bank_method_id" value="{{ $bankMethod->id }}" 
                                               class="text-blue-600 focus:ring-blue-500 bank-method" 
                                               data-bank-name="{{ $bankMethod->bank_name }}"
                                               data-account-number="{{ $bankMethod->account_number }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $bankMethod->bank_name }}</p>
                                        </div>
                                    </label>
                                    @empty
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-700">No bank transfer methods available. Please contact administrator.</p>
                                    </div>
                                    @endforelse
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
                                    @forelse($eWalletMethods as $eWalletMethod)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-green-50">
                                        <input type="radio" name="bank_method_id" value="{{ $eWalletMethod->id }}" 
                                               class="text-green-600 focus:ring-green-500 ewallet-method"
                                               data-wallet-name="{{ $eWalletMethod->bank_name }}"
                                               data-account-number="{{ $eWalletMethod->account_number }}">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $eWalletMethod->bank_name }}</p>
                                        </div>
                                    </label>
                                    @empty
                                    <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-700">No e-wallet methods available. Please contact administrator.</p>
                                    </div>
                                    @endforelse
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

                        <!-- Amount Field -->
                        <div class="mb-6">
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                @if($paymentType === 'first')
                                    Amount to Pay 
                                    <span class="text-gray-500">(Minimum: Rp {{ number_format($minimumDownPayment, 0) }})</span>
                                @else
                                    Final Payment Amount
                                @endif
                            </label>
                            
                            @if($paymentType === 'first')
                                <input type="number" name="amount_paid" id="amount_paid" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                    placeholder="Enter amount to pay"
                                    min="{{ $minimumDownPayment }}"
                                    max="{{ $remainingBalance }}"
                                    required
                                    oninput="updateAmountInfo(this.value)">
                                <p class="text-sm text-gray-500 mt-1">
                                    You can pay any amount between minimum down payment and full amount.
                                </p>
                                <div id="amountInfo" class="mt-2 text-sm text-blue-600 hidden">
                                    <p>After this payment, remaining balance: <span id="remainingAfterPayment">Rp 0</span></p>
                                </div>
                            @else
                                <div class="p-4 bg-gray-100 border border-gray-300 rounded-md">
                                    <p class="text-lg font-semibold text-gray-900 text-center">
                                        Rp {{ number_format($remainingBalance, 0) }}
                                    </p>
                                    <p class="text-sm text-gray-600 text-center mt-1">
                                        This is the remaining balance that needs to be paid in full.
                                    </p>
                                </div>
                                <input type="hidden" name="amount_paid" value="{{ $remainingBalance }}">
                            @endif
                            
                            @error('amount_paid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                                    Please complete the bank transfer or e-wallet payment and our staff will verify your payment.
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
                            <a href="{{ route('customer.reservations.show', $reservation) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Back to Reservation
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                @if($paymentType === 'first')
                                    Process Payment
                                @else
                                    Complete Final Payment
                                @endif
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
                const inputs = field.querySelectorAll('input[type="radio"]');
                inputs.forEach(input => {
                    input.checked = false;
                    input.removeAttribute('required');
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
                } else if (methodValue === 'e_wallet') {
                    bankInfo.classList.remove('hidden');
                    document.getElementById('eWalletFields').classList.remove('hidden');
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
                const fields = document.querySelectorAll(`[name="${fieldName}"]`);
                fields.forEach(field => {
                    field.setAttribute('required', 'required');
                });
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
                    alert('Please select a bank account for bank transfer');
                }
            } else if (methodValue === 'e_wallet') {
                const ewalletMethod = document.querySelector('.ewallet-method:checked');
                if (!ewalletMethod) {
                    isValid = false;
                    alert('Please select an e-wallet for payment');
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function updateAmountInfo(amount) {
            const amountInfo = document.getElementById('amountInfo');
            const remainingAfterPayment = document.getElementById('remainingAfterPayment');
            
            if (amount && amount >= {{ $minimumDownPayment }}) {
                const remaining = {{ $remainingBalance }} - amount;
                remainingAfterPayment.textContent = 'Rp ' + remaining.toLocaleString('id-ID');
                amountInfo.classList.remove('hidden');
            } else {
                amountInfo.classList.add('hidden');
            }
        }

        const amountInput = document.getElementById('amount_paid');
        if (amountInput && amountInput.value) {
            updateAmountInfo(amountInput.value);
        }
    });
    </script>
</x-app-layout>