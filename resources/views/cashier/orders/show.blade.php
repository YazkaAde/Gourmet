<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Order Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                            <p class="text-gray-600">Customer: {{ $order->user->name }}</p>
                            <p class="text-gray-600">Table: {{ $order->table_number }}</p>
                            <p class="text-gray-600">Date: {{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-xl font-bold text-gray-900 mt-2">
                                Total: Rp {{ number_format($order->total_price, 0) }}
                            </p>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                        <div class="space-y-3">
                            @foreach($order->carts as $cartItem)
                                <div class="flex justify-between items-center p-3 border rounded-lg">
                                    <div class="flex items-center">
                                        @if($cartItem->menu->image_url)
                                            <img src="{{ asset('storage/' . $cartItem->menu->image_url) }}" 
                                                 alt="{{ $cartItem->menu->name }}"
                                                 class="w-12 h-12 object-cover rounded mr-3"
                                                 onerror="this.style.display='none'">
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $cartItem->menu->name }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ $cartItem->quantity }}</p>
                                            <p class="text-sm text-gray-600">Price: Rp {{ number_format($cartItem->menu->price, 0) }}</p>
                                        </div>
                                    </div>
                                    <p class="font-medium text-gray-800">
                                        Rp {{ number_format($cartItem->menu->price * $cartItem->quantity, 0) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold mb-4">Update Order Status</h3>
                        <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST" class="flex items-center gap-4">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="p-2 border border-gray-300 rounded">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                                Update Status
                            </button>
                        </form>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="{{ route('cashier.orders.index') }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>