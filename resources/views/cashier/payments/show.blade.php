<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Details #') }}{{ $payment->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Payment Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Payment Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">Payment ID</p>
                                    <p class="font-medium">#{{ $payment->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Type</p>
                                    <p class="font-medium">
                                        @if($payment->order_id)
                                            Order Payment
                                            @if($payment->order && $payment->order->order_type)
                                                <span class="ml-2 px-2 py-1 text-xs rounded 
                                                    {{ $payment->order->order_type == 'dine_in' ? 'bg-blue-100 text-blue-800' : 'bg-orange-100 text-orange-800' }}">
                                                    {{ str_replace('_', ' ', ucfirst($payment->order->order_type)) }}
                                                </span>
                                            @endif
                                        @elseif($payment->reservation_id)
                                            Reservation Payment
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Status</p>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        @if($payment->status == 'paid') bg-green-100 text-green-800
                                        @elseif($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Amount</p>
                                    <p class="font-medium text-lg">Rp {{ number_format($payment->amount, 0) }}</p>
                                </div>
                                @if($payment->payment_method == 'cash' && $payment->amount_paid)
                                <div>
                                    <p class="text-sm text-gray-600">Amount Paid</p>
                                    <p class="font-medium">Rp {{ number_format($payment->amount_paid, 0) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Change</p>
                                    <p class="font-medium">Rp {{ number_format($payment->change, 0) }}</p>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm text-gray-600">Payment Date</p>
                                    <p class="font-medium">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                            <div class="space-y-3">
                                @if($payment->order_id && $payment->order && $payment->order->user)
                                <div>
                                    <p class="text-sm text-gray-600">Customer Name</p>
                                    <p class="font-medium">{{ $payment->order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Order Type</p>
                                    <p class="font-medium">
                                        <span class="{{ $payment->order->order_type == 'dine_in' ? 'text-blue-600' : 'text-orange-600' }}">
                                            {{ str_replace('_', ' ', ucfirst($payment->order->order_type)) }}
                                        </span>
                                    </p>
                                </div>
                                @elseif($payment->reservation_id && $payment->reservation && $payment->reservation->user)
                                <div>
                                    <p class="text-sm text-gray-600">Customer Name</p>
                                    <p class="font-medium">{{ $payment->reservation->user->name }}</p>
                                </div>
                                @else
                                <div>
                                    <p class="text-sm text-gray-600">Customer Name</p>
                                    <p class="font-medium text-red-600">Customer data not available</p>
                                </div>
                                @endif
                                
                                @if($payment->order_id && $payment->order)
                                <div>
                                    <p class="text-sm text-gray-600">Table Number</p>
                                    <p class="font-medium">{{ $payment->order->table_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Order Date</p>
                                    <p class="font-medium">{{ $payment->order->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Order Status</p>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        @if($payment->order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($payment->order->status == 'processing') bg-blue-100 text-blue-800
                                        @elseif($payment->order->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($payment->order->status) }}
                                    </span>
                                </div>
                                @elseif($payment->reservation_id && $payment->reservation)
                                <div>
                                    <p class="text-sm text-gray-600">Table Number</p>
                                    <p class="font-medium">{{ $payment->reservation->table_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Reservation Date</p>
                                    <p class="font-medium">{{ $payment->reservation->reservation_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Reservation Time</p>
                                    <p class="font-medium">{{ $payment->reservation->reservation_time }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">End Time</p>
                                    <p class="font-medium">{{ $payment->reservation->end_time }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Reservation Status</p>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                                        @if($payment->reservation->status == 'confirmed') bg-green-100 text-green-800
                                        @elseif($payment->reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($payment->reservation->status == 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($payment->reservation->status) }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Payment History untuk Reservation -->
                    @if($payment->reservation_id && $payment->reservation && $payment->reservation->payments->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Payment History</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payment->reservation->payments->sortBy('created_at') as $reservationPayment)
                                    <tr class="{{ $reservationPayment->id == $payment->id ? 'bg-blue-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            #{{ $reservationPayment->id }}
                                            @if($reservationPayment->id == $payment->id)
                                                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Current</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $reservationPayment->created_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($reservationPayment->amount, 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                @if($reservationPayment->status == 'paid') bg-green-100 text-green-800
                                                @elseif($reservationPayment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($reservationPayment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $reservationPayment->payment_method)) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-right font-medium">Total Paid:</td>
                                        <td class="px-6 py-4 font-bold">
                                            Rp {{ number_format($payment->reservation->payments->where('status', 'paid')->sum('amount'), 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    @php
                                        $totalAmount = $payment->reservation->total_amount ?? $payment->reservation->payments->sum('amount');
                                        $totalPaid = $payment->reservation->payments->where('status', 'paid')->sum('amount');
                                        $remaining = $totalAmount - $totalPaid;
                                    @endphp
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-right font-medium">Total Amount:</td>
                                        <td class="px-6 py-4 font-bold">
                                            Rp {{ number_format($totalAmount, 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-right font-medium">Remaining Balance:</td>
                                        <td class="px-6 py-4 font-bold {{ $remaining > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                            Rp {{ number_format($remaining, 0) }}
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Order Items -->
                    @if($payment->order_id && $payment->order)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($payment->order->orderItems as $orderItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($orderItem->menu->image_url)
                                                <img src="{{ asset('storage/' . $orderItem->menu->image_url) }}" 
                                                    alt="{{ $orderItem->menu->name }}"
                                                    class="w-10 h-10 object-cover rounded mr-3"
                                                    onerror="this.style.display='none'">
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $orderItem->menu->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $orderItem->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($orderItem->price, 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($orderItem->price * $orderItem->quantity, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Total Amount:</td>
                                        <td class="px-6 py-4 font-bold">Rp {{ number_format($payment->order->total_price, 0) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Pre-Order Items -->
                    @if($payment->reservation_id && $payment->reservation)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Reservation Menu Items</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($payment->reservation->preOrderItems && $payment->reservation->preOrderItems->count() > 0)
                                        @foreach($payment->reservation->preOrderItems as $item)
                                        @if($item->menu)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->menu->image_url)
                                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                                        alt="{{ $item->menu->name }}"
                                                        class="w-10 h-10 object-cover rounded mr-3"
                                                        onerror="this.style.display='none'">
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                                        <p class="text-sm text-gray-500">Pre-Order</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0) }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @elseif($payment->reservation->orderItems && $payment->reservation->orderItems->count() > 0)
                                        @foreach($payment->reservation->orderItems as $item)
                                        @if($item->menu)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->menu->image_url)
                                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                                        alt="{{ $item->menu->name }}"
                                                        class="w-10 h-10 object-cover rounded mr-3"
                                                        onerror="this.style.display='none'">
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                                        <p class="text-sm text-gray-500">Order Item</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0) }}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    @elseif($payment->reservation->orders && $payment->reservation->orders->count() > 0)
                                        @foreach($payment->reservation->orders as $order)
                                            @foreach($order->orderItems as $item)
                                            @if($item->menu)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @if($item->menu->image_url)
                                                        <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                                            alt="{{ $item->menu->name }}"
                                                            class="w-10 h-10 object-cover rounded mr-3"
                                                            onerror="this.style.display='none'">
                                                        @endif
                                                        <div>
                                                            <p class="font-medium text-gray-900">{{ $item->menu->name }}</p>
                                                            <p class="text-sm text-gray-500">From Order #{{ $order->id }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price, 0) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->price * $item->quantity, 0) }}</td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        @endforeach
                                    @else
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No menu items found for this reservation.
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6 border-t">
                        <a href="{{ route('cashier.payments.index') }}" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                            Back to Payments
                        </a>

                        <div class="flex gap-3">
                            @if($payment->status == 'pending')
                                @if($payment->payment_method != 'cash')
                                    <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST" id="confirmForm">
                                        @csrf
                                        <button type="submit" 
                                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                                onclick="return confirm('Confirm this {{ $payment->payment_method }} payment?')">
                                            Confirm Payment
                                        </button>
                                    </form>
                                @else
                                    <button type="button" 
                                            onclick="confirmCashPayment()"
                                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                        Confirm Cash Payment
                                    </button>
                                @endif
                                
                                <form action="{{ route('cashier.payments.reject', $payment) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                            onclick="return confirm('Reject this payment?')">
                                        Reject Payment
                                    </button>
                                </form>
                            @endif

                            @if($payment->status == 'paid')
                                <a href="{{ route('cashier.payments.receipt', $payment) }}" 
                                target="_blank"
                                class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition">
                                    Print Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form tersembunyi untuk cash payment -->
    @if($payment->status == 'pending' && $payment->payment_method == 'cash')
    <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST" id="cashPaymentForm" class="hidden">
        @csrf
        <input type="number" name="amount_paid" id="amountPaidInput" required>
    </form>
    @endif

    <script>
    function confirmCashPayment() {
        const amountDue = {{ $payment->amount }};
        const amountPaid = prompt('Please enter the amount paid by customer:\n\nAmount due: Rp ' + amountDue.toLocaleString('id-ID'));
        
        if (amountPaid === null) {
            return;
        }
        
        const paid = parseFloat(amountPaid);
        
        if (isNaN(paid) || paid < amountDue) {
            alert('Invalid amount! Please enter a valid amount that is at least Rp ' + amountDue.toLocaleString('id-ID'));
            return;
        }
        
        const change = paid - amountDue;
        const confirmMessage = 'Amount Paid: Rp ' + paid.toLocaleString('id-ID') + 
                             '\nAmount Due: Rp ' + amountDue.toLocaleString('id-ID') +
                             '\nChange: Rp ' + change.toLocaleString('id-ID') +
                             '\n\nConfirm this cash payment?';
        
        if (confirm(confirmMessage)) {
            document.getElementById('amountPaidInput').value = paid;
            document.getElementById('cashPaymentForm').submit();
        }
    }
    </script>
</x-app-layout>