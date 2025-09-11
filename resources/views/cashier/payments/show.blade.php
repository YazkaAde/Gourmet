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
                                @if($payment->order_id && $payment->order)
                                @if($payment->order_id && $payment->order && $payment->order->user)
                                <div>
                                    <p class="text-sm text-gray-600">Customer Name</p>
                                    <p class="font-medium">{{ $payment->order->user->name }}</p>
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
                                <div>
                                    <p class="text-sm text-gray-600">Table Number</p>
                                    <p class="font-medium">{{ $payment->order->table_number }}</p>
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
                                    <p class="text-sm text-gray-600">Customer Name</p>
                                    <p class="font-medium">{{ $payment->reservation->user->name }}</p>
                                </div>
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

                    <!-- Order Items (jika order payment) -->
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
                                    @foreach($payment->order->carts as $cartItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($cartItem->menu->image_url)
                                                <img src="{{ asset('storage/' . $cartItem->menu->image_url) }}" 
                                                     alt="{{ $cartItem->menu->name }}"
                                                     class="w-10 h-10 object-cover rounded mr-3"
                                                     onerror="this.style.display='none'">
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $cartItem->menu->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cartItem->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($cartItem->menu->price, 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($cartItem->menu->price * $cartItem->quantity, 0) }}</td>
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

                    <!-- Pre-Order Items (jika reservation payment) -->
                    @if($payment->reservation_id && $payment->reservation && $payment->reservation->preOrderItems->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Pre-Order Items</h3>
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
                                    @foreach($payment->reservation->preOrderItems as $preOrderItem)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($preOrderItem->menu->image_url)
                                                <img src="{{ asset('storage/' . $preOrderItem->menu->image_url) }}" 
                                                     alt="{{ $preOrderItem->menu->name }}"
                                                     class="w-10 h-10 object-cover rounded mr-3"
                                                     onerror="this.style.display='none'">
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $preOrderItem->menu->name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $preOrderItem->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($preOrderItem->price, 0) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($preOrderItem->price * $preOrderItem->quantity, 0) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-medium">Menu Total:</td>
                                        <td class="px-6 py-4 font-bold">
                                            Rp {{ number_format($payment->reservation->preOrderItems->sum(function($item) {
                                                return $item->price * $item->quantity;
                                            }), 0) }}
                                        </td>
                                    </tr>
                                </tfoot>
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
                                <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
                                            onclick="return confirm('Confirm this payment?')">
                                        Confirm Payment
                                    </button>
                                </form>
                                
                                @if($payment->payment_method == 'cash')
                                <button type="button" 
                                        onclick="openCashModal()"
                                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                    Process Cash
                                </button>
                                @endif
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

    <!-- Cash Payment Modal -->
    @if($payment->status == 'pending' && $payment->payment_method == 'cash')
    <div id="cashModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg w-96 p-6">
            <h3 class="text-lg font-medium mb-4">Process Cash Payment</h3>
            <form action="{{ route('cashier.payments.process-cash', $payment) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                    <input type="number" 
                           name="amount_paid" 
                           class="w-full p-2 border border-gray-300 rounded"
                           placeholder="Enter amount paid"
                           min="{{ $payment->amount }}"
                           required
                           step="500">
                    <p class="text-sm text-gray-500 mt-1">Minimum: Rp {{ number_format($payment->amount, 0) }}</p>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeCashModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Process</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCashModal() {
            document.getElementById('cashModal').classList.remove('hidden');
        }

        function closeCashModal() {
            document.getElementById('cashModal').classList.add('hidden');
        }

        document.getElementById('cashModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCashModal();
            }
        });
    </script>
    @endif
</x-app-layout>