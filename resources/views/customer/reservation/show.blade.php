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

            @if (session('info'))
                <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg">
                    {{ session('info') }}
                </div>
            @endif

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
                                @elseif($reservation->status == 'completed') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $reservation->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Capacity</p>
                            <p class="font-medium">{{ $reservation->table_capacity }} people</p>
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
                        <h4 class="text-md font-semibold mb-3">Reservation Fee Breakdown</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Base Price (Table Fee)</p>
                                <p class="font-medium">Rp {{ number_format($reservation->reservation_fee, 0) }}</p>
                            </div>
                            @if($reservation->orderItems->count() > 0)
                            <div>
                                <p class="text-sm text-gray-600">Menu Total</p>
                                <p class="font-medium">Rp {{ number_format($reservation->menu_total, 0) }}</p>
                            </div>
                            @endif
                            @if($reservation->table_capacity >= 8)
                            <div>
                                <p class="text-sm text-gray-600">Discount (20%)</p>
                                <p class="font-medium text-green-600">- Rp {{ number_format(($reservation->table_capacity * 10000) * 0.2, 0) }}</p>
                            </div>
                            @endif
                            <div class="col-span-2 border-t pt-2">
                                <p class="text-sm text-gray-600 font-bold">Total Reservation Fee</p>
                                <p class="font-bold text-lg">Rp {{ number_format($reservation->total_amount, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Down Payment Required (10%)</p>
                                <p class="font-medium">Rp {{ number_format($reservation->down_payment_amount, 0) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Items Section -->
            @if($reservation->orderItems->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Pre-Order Menu Items</h3>
                    
                    <div class="space-y-4">
                        @foreach($reservation->orderItems as $item)
                        <div class="flex justify-between items-center border-b pb-4">
                            <div class="flex items-center">
                                @if($item->menu->image_url)
                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                        alt="{{ $item->menu->name }}"
                                        class="w-16 h-16 object-cover rounded mr-4"
                                        onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <h4 class="font-semibold">{{ $item->menu->name }}</h4>
                                    <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0) }}</p>
                                </div>
                            </div>
                            <p class="font-medium">Rp {{ number_format($item->total_price, 0) }}</p>
                        </div>
                        @endforeach
                        
                        <div class="flex justify-between font-bold text-lg pt-4 border-t">
                            <span>Menu Total:</span>
                            <span>Rp {{ number_format($reservation->orderItems->sum('total_price'), 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Payment Section -->
            @php
            $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
            $minimumDownPayment = $reservation->total_amount * 0.1;
            $showPaymentButton = $reservation->status == 'pending' && $totalPaid < $minimumDownPayment;
            @endphp

            @if($showPaymentButton)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-lg font-semibold text-gray-900">Payment Required</h3>
                        <p class="text-gray-600">Please pay the down payment to confirm your reservation.</p>
                    </div>
                    <a href="{{ route('customer.reservations.payment.create', $reservation) }}" 
                    class="px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium text-center">
                        Pay Down Payment (Rp {{ number_format($minimumDownPayment, 0) }})
                    </a>
                </div>
            </div>
            </div>
            @endif

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

            @if($reservation->status !== 'cancelled' && $reservation->payments()->where('status', 'paid')->exists())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Reservation Proof</h3>
                    
                    <div id="printable-confirmation" class="bg-gray-50 p-6 rounded-lg">
                        <!-- ... konten receipt ... -->
                    </div>

                    <div class="mt-6 text-center">
                        <button onclick="printConfirmation()" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            Print Confirmation
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <div class="flex flex-col md:flex-row gap-3">
                            <a href="{{ route('customer.reservations.index') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium text-center">
                                Back to Reservations
                            </a>
                            
                            <a href="{{ route('customer.menu.index') }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium text-center">
                                Back to Menu
                            </a>
                        </div>

                        <div class="flex flex-wrap gap-3 justify-center md:justify-end">
                            @php
                                $totalPaid = $reservation->payments()->where('status', 'paid')->sum('amount');
                                $isFullyPaid = $totalPaid >= $reservation->total_amount;
                            @endphp
                            
                            @if(!$isFullyPaid && $reservation->status !== 'cancelled')
                                <a href="{{ route('customer.reservations.payment.create', $reservation) }}" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors font-medium">
                                    Pay {{ $totalPaid > 0 ? 'Remaining' : 'Now' }}
                                </a>
                            @endif
                            
                            @if($reservation->status == 'pending')
                            <a href="{{ route('customer.reservations.edit', $reservation) }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium">
                                Edit Reservation
                            </a>
                            
                            <form action="{{ route('customer.reservations.cancel', $reservation) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors font-medium"
                                        onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                    Cancel Reservation
                                </button>
                            </form>
                            @endif

                            @if($reservation->status == 'confirmed')
                            <form action="{{ route('customer.reservations.cancel', $reservation) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors font-medium"
                                        onclick="return confirm('Are you sure you want to cancel this reservation? {{ $reservation->hasOrder() ? "Cancellation fee: Rp " . number_format($reservation->cancellation_fee, 0) : "" }}')">
                                    Cancel Reservation
                                    @if($reservation->hasOrder())
                                    <span class="text-xs block">(Fee: Rp {{ number_format($reservation->cancellation_fee, 0) }})</span>
                                    @endif
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Section (jika ada) -->
            @if($reservation->orders()->exists())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                    
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
                                    @foreach($order->orderItems as $orderItem)
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            @if($orderItem->menu->image_url)
                                                <img src="{{ asset('storage/' . $orderItem->menu->image_url) }}" 
                                                    alt="{{ $orderItem->menu->name }}"
                                                    class="w-10 h-10 object-cover rounded mr-3"
                                                    onerror="this.style.display='none'">
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium">{{ $orderItem->menu->name }}</p>
                                                <p class="text-xs text-gray-600">Qty: {{ $orderItem->quantity }} × Rp {{ number_format($orderItem->price, 0) }}</p>
                                            </div>
                                        </div>
                                        <p class="text-sm font-medium">Rp {{ number_format($orderItem->total_price, 0) }}</p>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tombol Bayar untuk Order yang belum lunas -->
                            @if($order->status !== 'completed' && $order->status !== 'cancelled')
                            <div class="mt-4 pt-4 border-t">
                                <a href="{{ route('customer.orders.payment.create', $order) }}" 
                                   class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    Pay Order
                                </a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
    function printConfirmation() {
        var printContents = document.getElementById('printable-confirmation').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }
    </script>
</x-app-layout>
