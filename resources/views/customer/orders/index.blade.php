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
                                        <div class="space-y-4">
                                        @foreach($order->orderItems as $orderItem)
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center flex-grow">
                                                @if($orderItem->menu->image_url)
                                                    <img src="{{ asset('storage/' . $orderItem->menu->image_url) }}" 
                                                        alt="{{ $orderItem->menu->name }}"
                                                        class="w-10 h-10 object-cover rounded mr-3"
                                                        onerror="this.style.display='none'">
                                                @endif
                                                <div class="flex-grow">
                                                    <p class="text-gray-800">{{ $orderItem->menu->name }}</p>
                                                    <p class="text-sm text-gray-600">Qty: {{ $orderItem->quantity }}</p>
                                                    <p class="text-gray-800">Rp {{ number_format($orderItem->total_price, 0) }}</p>
                                                    
                                                    @if($order->status == 'completed' && $order->payment && $order->payment->status == 'paid')
                                                        @php
                                                            $userReview = $userReviews[$orderItem->menu_id] ?? null;
                                                        @endphp
                                                        
                                                        @if(!$userReview)
                                                            <div class="mt-2">
                                                                <a href="{{ route('customer.reviews.create', ['order' => $order, 'menu' => $orderItem->menu]) }}" 
                                                                class="inline-flex items-center px-3 py-1 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700 transition">
                                                                    ✩ Write Review
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="mt-2 flex items-center gap-2">
                                                                <span class="px-2 py-1 bg-green-100 text-green-800 text-sm rounded-md">
                                                                    ✓ Reviewed ({{ $userReview->rating }}★)
                                                                </span>
                                                                <form action="{{ route('customer.reviews.destroy', $userReview) }}" method="POST" class="inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" 
                                                                            class="px-2 py-1 bg-red-100 text-red-800 text-sm rounded-md hover:bg-red-200"
                                                                            onclick="return confirm('Are you sure you want to delete your review?')">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        </div>
                                    </div>

                                    <!-- Payment and Cancel Buttons -->
                                    <div class="mt-4 pt-4 border-t flex gap-3 items-center">
                                        @if($order->status == 'pending')
                                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                                        onclick="return confirm('Are you sure you want to cancel this order?')">
                                                    Cancel Order
                                                </button>
                                            </form>
                                        @endif

                                        @if($order->status == 'completed')
                                            @if($order->payment)
                                                <div class="flex items-center gap-2">
                                                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded text-sm">
                                                        Payment: {{ ucfirst($order->payment->status) }}
                                                    </span>
                                                    @if($order->payment->status == 'paid')
                                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">
                                                            {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <a href="{{ route('customer.orders.payment.create', $order) }}" 
                                                   class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                                    Pay Now
                                                </a>
                                            @endif
                                        @endif
                                        
                                        <a href="{{ route('customer.orders.show', $order) }}" 
                                           class="ml-auto px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                            View Details
                                        </a>
                                    </div>
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