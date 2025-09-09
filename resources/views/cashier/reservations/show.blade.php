<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation Details') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Reservation Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-semibold">Reservation Information</h3>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Reservation ID</p>
                            <p class="font-medium">#{{ $reservation->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Customer Name</p>
                            <p class="font-medium">{{ $reservation->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Customer Email</p>
                            <p class="font-medium">{{ $reservation->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $reservation->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Capacity</p>
                            <p class="font-medium">{{ $reservation->table->table_capacity }} people</p>
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
                        <div>
                            <p class="text-sm text-gray-600">Created At</p>
                            <p class="font-medium">{{ $reservation->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($reservation->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">Notes</p>
                        <p class="font-medium">{{ $reservation->notes }}</p>
                    </div>
                    @endif

                    <!-- Reservation Fee Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Reservation Fee</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Base Price</p>
                                <p class="font-medium">Rp {{ number_format($reservation->table->table_capacity * 10000, 0) }}</p>
                            </div>
                            @if($reservation->table->table_capacity >= 8)
                            <div>
                                <p class="text-sm text-gray-600">Discount (20%)</p>
                                <p class="font-medium text-green-600">- Rp {{ number_format(($reservation->table->table_capacity * 10000) * 0.2, 0) }}</p>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Total Reservation Fee</p>
                                <p class="font-medium">Rp {{ number_format($reservation->reservation_fee, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Down Payment Required (10%)</p>
                                <p class="font-medium">Rp {{ number_format($reservation->reservation_fee * 0.1, 0) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Update Form -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Update Reservation Status</h4>
                        <form action="{{ route('cashier.reservations.update-status', $reservation) }}" method="POST">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                                <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                                    <option value="confirmed" {{ $reservation->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $reservation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($reservation->payments()->exists())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Payment History</h3>
                    <div class="space-y-3">
                        @foreach($reservation->payments as $payment)
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">Payment #{{ $payment->id }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }} • 
                                        Rp {{ number_format($payment->amount, 0) }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium 
                                    @if($payment->status == 'paid') bg-green-100 text-green-800
                                    @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                            @if($payment->payment_method == 'cash' && $payment->amount_paid)
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>
                                        <p class="text-gray-600">Amount Paid:</p>
                                        <p>Rp {{ number_format($payment->amount_paid, 0) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-600">Change:</p>
                                        <p>Rp {{ number_format($payment->change, 0) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($payment->notes)
                            <div class="mt-2">
                                <p class="text-sm text-gray-600">Notes: {{ $payment->notes }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Pre-Order Section (jika ada) -->
            @if($reservation->orders()->exists())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Pre-Order Items</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservation->orders as $order)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="font-semibold">Order #{{ $order->id }}</h4>
                                    <p class="text-sm text-gray-600">Status: 
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'processing') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </p>
                                </div>
                                <p class="font-bold">Total: Rp {{ number_format($order->total_price, 0) }}</p>
                            </div>

                            <div class="border-t pt-4">
                                <h5 class="font-medium mb-2">Items:</h5>
                                <div class="space-y-2">
                                    @foreach($order->carts as $cartItem)
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            @if($cartItem->menu->image_url)
                                                <img src="{{ asset('storage/' . $cartItem->menu->image_url) }}" 
                                                    alt="{{ $cartItem->menu->name }}"
                                                    class="w-10 h-10 object-cover rounded mr-3"
                                                    onerror="this.style.display='none'">
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium">{{ $cartItem->menu->name }}</p>
                                                <p class="text-xs text-gray-600">Qty: {{ $cartItem->quantity }} × Rp {{ number_format($cartItem->menu->price, 0) }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium">Rp {{ number_format($cartItem->menu->price * $cartItem->quantity, 0) }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <a href="{{ route('cashier.reservations.index') }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Back to Reservations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>