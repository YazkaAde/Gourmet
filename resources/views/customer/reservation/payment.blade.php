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

                    <!-- Reservation Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Table Capacity</p>
                                <p class="font-medium">{{ $reservation->table->table_capacity }} people</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Reservation Fee</p>
                                <p class="font-medium">Rp {{ number_format($reservation->reservation_fee, 0) }}</p>
                            </div>
                            @if($reservation->preOrderItems->count() > 0)
                            <div>
                                <p class="text-sm text-gray-600">Menu Total</p>
                                <p class="font-medium">Rp {{ number_format($reservation->preOrderItems->sum(function($item) { return $item->price * $item->quantity; }), 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Amount</p>
                                <p class="font-medium">Rp {{ number_format($totalAmount, 0) }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Minimum Down Payment (10%)</p>
                                <p class="font-medium">Rp {{ number_format($minimumDownPayment, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Remaining Balance</p>
                                <p class="font-medium">Rp {{ number_format($totalAmount - $minimumDownPayment, 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                    
                    <form action="{{ route('customer.reservations.payment.store', $reservation) }}" method="POST">
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

                        <!-- Amount Paid Field -->
                        <div class="mb-6">
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                Amount to Pay (Minimum: Rp {{ number_format($minimumDownPayment, 0) }})
                            </label>
                            <input type="number" name="amount_paid" id="amount_paid" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                placeholder="Enter amount to pay"
                                min="{{ $minimumDownPayment }}"
                                required>
                            @error('amount_paid')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500 mt-1">
                                Minimum down payment is 10% of reservation fee. You can pay more if you want.
                            </p>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customer.reservations.index', $reservation) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Back to Reservation
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
</x-app-layout>