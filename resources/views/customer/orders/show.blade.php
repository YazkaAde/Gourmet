<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} #{{ $order->id }}
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Order ID</p>
                            <p class="font-medium">#{{ $order->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-yellow-100 text-yellow-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Table Number</p>
                            <p class="font-medium">{{ $order->table_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Price</p>
                            <p class="font-medium">Rp {{ number_format($order->total_price, 0) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Order Date</p>
                            <p class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($order->payment)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Payment Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Payment Status</p>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($order->payment->status == 'paid') bg-green-100 text-green-800
                                    @elseif($order->payment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->payment->status == 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->payment->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Payment Method</p>
                                <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                            </div>
                            @if($order->payment->payment_method == 'cash')
                            <div>
                                <p class="text-sm text-gray-600">Amount Paid</p>
                                <p class="font-medium">Rp {{ number_format($order->payment->amount_paid, 0) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Change</p>
                                <p class="font-medium">Rp {{ number_format($order->payment->change, 0) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Menu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->carts as $cartItem)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($cartItem->menu->image_url)
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/'.$cartItem->menu->image_url) }}" alt="{{ $cartItem->menu->name }}">
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $cartItem->menu->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $cartItem->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($cartItem->menu->price, 0) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        Rp {{ number_format($cartItem->quantity * $cartItem->menu->price, 0) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($order->total_price, 0) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->status == 'completed')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-lg font-semibold mb-4">Reviews</h4>
                        <div class="space-y-4">
                            @foreach($order->carts as $cartItem)
                                @php
                                    $userReview = $cartItem->menu->reviews->firstWhere('user_id', auth()->id());
                                @endphp
                                
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($cartItem->menu->image_url)
                                            <img src="{{ asset('storage/' . $cartItem->menu->image_url) }}" 
                                                alt="{{ $cartItem->menu->name }}"
                                                class="w-10 h-10 object-cover rounded mr-3"
                                                onerror="this.style.display='none'">
                                        @endif
                                        <span class="font-medium">{{ $cartItem->menu->name }}</span>
                                    </div>
                                    
                                    <div>
                                        @if($order->payment && $order->payment->status == 'paid')
                                            @if(!$userReview)
                                                <a href="{{ route('customer.reviews.create', ['order' => $order, 'menu' => $cartItem->menu]) }}" 
                                                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                                                    ✩ Write Review
                                                </a>
                                            @else
                                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                                    ✓ Reviewed
                                                </span>
                                                <form action="{{ route('customer.reviews.destroy', $userReview) }}" method="POST" class="inline ml-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm hover:bg-red-200"
                                                            onclick="return confirm('Are you sure you want to delete your review?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500">Complete payment to review</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if($order->status == 'completed' && !$order->payment)
                    <div class="mt-6 text-center">
                        <a href="{{ route('customer.orders.payment.create', $order) }}" 
                           class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                            Process Payment
                        </a>
                    </div>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('customer.orders.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>