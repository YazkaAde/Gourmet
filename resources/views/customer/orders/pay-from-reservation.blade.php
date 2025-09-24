<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pay Order from Reservation') }} #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="font-medium">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reservation ID</p>
                            <p class="font-medium">#{{ $order->reservation->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $order->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Order Status</p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Order Items</h4>
                        <div class="space-y-2">
                            @foreach($order->orderItems as $item)
                            <div class="flex justify-between">
                                <span>{{ $item->menu->name }} (x{{ $item->quantity }})</span>
                                <span>Rp {{ number_format($item->price * $item->quantity, 0) }}</span>
                            </div>
                            @endforeach
                            <div class="flex justify-between font-bold border-t pt-2">
                                <span>Total:</span>
                                <span>Rp {{ number_format($order->total_price, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Payment Summary</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Order Total:</span>
                                <span>Rp {{ number_format($order->total_price, 0) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Already Paid (via Reservation):</span>
                                <span>Rp {{ number_format($paidForMenu, 0) }}</span>
                            </div>
                            <div class="flex justify-between font-bold border-t pt-2">
                                <span>Remaining Balance:</span>
                                <span>Rp {{ number_format($remainingPayment, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                    
                    <form action="{{ route('customer.orders.pay-from-reservation.store', $order) }}" method="POST">
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
                        </div>

                        <input type="hidden" name="amount_paid" value="{{ $remainingPayment }}">

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customer.reservations.show', $order->reservation) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Back to Reservation
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                Pay Rp {{ number_format($remainingPayment, 0) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>