<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($orders->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1M8 7H6a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2M8 7h8"/>
                    </svg>
                    <p class="text-gray-600 mb-4">You don't have any orders yet</p>
                    <a href="{{ route('customer.menu.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 transition">
                        Browse Menu
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="p-4 border rounded-lg bg-white hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">Order #{{ $order->id }}</h3>
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
                                            <p class="text-lg font-bold text-gray-900 mt-2">
                                                Total: Rp {{ number_format($order->total_price, 0) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="border-t pt-4 mt-4">
                                        <h4 class="font-semibold text-gray-700 mb-2">Order Items:</h4>
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
                                                            <p class="text-gray-800">{{ $cartItem->menu->name }}</p>
                                                            <p class="text-sm text-gray-600">Qty: {{ $cartItem->quantity }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-800">Rp {{ number_format($cartItem->menu->price * $cartItem->quantity, 0) }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($order->status == 'pending')
                                        <div class="mt-4 pt-4 border-t">
                                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    Cancel Order
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>