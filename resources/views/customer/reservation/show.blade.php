<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation Details') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Notification Section --}}
            @if (session('success'))
            <div class="session-notification p-4 mb-4 bg-white border border-green-200 rounded-lg shadow-sm" data-type="success" data-auto-close="true">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                    <button type="button" class="close-session-notification ml-3 inline-flex text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            @if (session('info'))
            <div class="session-notification p-4 mb-4 bg-white border border-blue-200 rounded-lg shadow-sm" data-type="info" data-auto-close="true">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-blue-800">
                            {{ session('info') }}
                        </p>
                    </div>
                    <button type="button" class="close-session-notification ml-3 inline-flex text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="session-notification p-4 mb-4 bg-white border border-red-200 rounded-lg shadow-sm" data-type="error" data-auto-close="true">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                    <button type="button" class="close-session-notification ml-3 inline-flex text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            @endif

            {{-- Reservation Information --}}
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
                                @if($reservation->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                                @elseif($reservation->status == 'completed') bg-green-100 text-green-800
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
                            <p class="font-medium">{{ $reservation->formatted_reservation_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reservation Start</p>
                            <p class="font-medium">{{ $reservation->formatted_reservation_time }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reservation End</p>
                            <p class="font-medium">{{ $reservation->formatted_end_time }}</p>
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

                    {{-- Reservation Fee Summary --}}
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

            {{-- Menu Items Section --}}
            @if($reservation->orderItems->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Pre-Order Menu Items ({{ $reservation->orderItems->count() }} items)</h3>
                        
                        @if($reservation->canReduceMenu())
                        <form action="{{ route('customer.reservations.menu.clear', $reservation) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm"
                                    onclick="return confirm('Are you sure you want to clear all menu items?')">
                                Clear All
                            </button>
                        </form>
                        @endif
                    </div>
                    
                    {{-- Debug Info --}}
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700">
                            Total Menu Items: <strong>{{ $reservation->orderItems->count() }}</strong> | 
                            Menu Total: <strong>Rp {{ number_format($reservation->menu_total, 0) }}</strong>
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($reservation->orderItems->groupBy('menu_id') as $menuId => $menuItems)
                        @php 
                            $item = $menuItems->first(); 
                            $totalQuantity = $menuItems->sum('quantity');
                            $totalPrice = $menuItems->sum('total_price');
                            $canReduce = $reservation->canReduceMenu();
                        @endphp
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-4 gap-4">
                            <div class="flex items-center flex-1">
                                @if($item->menu->image_url)
                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                        alt="{{ $item->menu->name }}"
                                        class="w-16 h-16 object-cover rounded mr-4"
                                        onerror="this.style.display='none'">
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-semibold">{{ $item->menu->name }}</h4>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item->price, 0) }} per item</p>
                                    <p class="text-xs text-gray-500">Item ID: {{ $item->id }}, Menu ID: {{ $item->menu_id }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 flex-wrap">
                                @if($reservation->canAddMenu())
                                <div class="flex items-center border rounded-lg overflow-hidden">
                                    @if($canReduce)
                                    <button type="button" 
                                            class="px-3 py-2 bg-gray-200 hover:bg-gray-300 decrease-quantity transition-colors"
                                            data-item-id="{{ $item->id }}"
                                            data-can-reduce="true">
                                        -
                                    </button>
                                    @else
                                    <button type="button" 
                                            class="px-3 py-2 bg-gray-100 text-gray-400 cursor-not-allowed"
                                            disabled
                                            title="Cannot reduce items when order is processing">
                                        -
                                    </button>
                                    @endif
                                    
                                    <input type="number" 
                                        name="quantity" 
                                        value="{{ $totalQuantity }}" 
                                        min="1" 
                                        class="w-16 px-2 py-2 text-center border-0 quantity-input"
                                        data-item-id="{{ $item->id }}"
                                        data-price="{{ $item->price }}"
                                        data-original-value="{{ $totalQuantity }}"
                                        {{ !$canReduce ? 'readonly' : '' }}
                                        {{ !$canReduce ? 'style="background-color: #f9fafb;"' : '' }}>
                                    
                                    <button type="button" 
                                            class="px-3 py-2 bg-gray-200 hover:bg-gray-300 increase-quantity transition-colors"
                                            data-item-id="{{ $item->id }}">
                                        +
                                    </button>
                                </div>
                                
                                @if($canReduce)
                                <form action="{{ route('customer.reservations.menu.remove', [$reservation, $item]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors"
                                            onclick="return confirm('Remove this item?')">
                                        Remove
                                    </button>
                                </form>
                                @endif
                                @else
                                <div class="text-center">
                                    <p class="text-sm text-gray-600">Qty: {{ $totalQuantity }}</p>
                                    <p class="text-xs text-gray-500">
                                        @if($reservation->hasProcessingOrCompletedOrder())
                                            @php
                                                $orderStatus = $reservation->orders()->first()->status ?? 'unknown';
                                            @endphp
                                            Status: {{ ucfirst($orderStatus) }}
                                        @else
                                            Cannot be modified
                                        @endif
                                    </p>
                                </div>
                                @endif
                                @if($reservation->hasProcessingOrder())
                                <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-orange-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-orange-700 font-medium">Order is being processed</p>
                                            <p class="text-orange-600 text-sm">Changes require two-step confirmation and may affect preparation time</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <p class="font-medium w-24 text-right item-total" data-item-id="{{ $item->id }}">
                                    Rp {{ number_format($totalPrice, 0) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    {{-- Total Summary --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold">Total Menu:</span>
                            <span class="text-lg font-bold" id="menu-total">Rp {{ number_format($reservation->menu_total, 0) }}</span>
                        </div>
                    </div>

                    @if($reservation->canAddMenu())
                    <div class="mt-6 pt-6 border-t border-gray-200 hidden" id="changes-section">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                            <span class="text-sm text-gray-600 text-center md:text-left" id="changes-message">Changes pending confirmation</span>
                            <div class="flex gap-2 flex-wrap justify-center">
                                <button type="button" 
                                        id="cancel-changes"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium">
                                    Cancel Changes
                                </button>
                                <button type="button" 
                                        id="confirm-changes"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors font-medium">
                                    Confirm Changes
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="text-center py-8">
                        <p class="text-gray-500">No menu items added to this reservation.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Add Menu Items Section --}}
            @if($reservation->isMenuEditable())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Add Menu Items</h3>
                    <button type="button" 
                            onclick="openAddMenuModal()"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        + Add Menu Items
                    </button>
                </div>
            </div>
            @else
            @if($reservation->hasProcessingOrCompletedOrder())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-blue-700 font-medium">Menu items cannot be modified</p>
                            <p class="text-blue-600 text-sm">
                                @php
                                    $orderStatus = $reservation->orders()->first()->status ?? 'unknown';
                                @endphp
                                Your order is currently <span class="font-semibold">{{ $orderStatus }}</span> and menu items can no longer be changed.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif

            {{-- Payment Section --}}
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

            {{-- Reviews Section untuk Completed Reservation --}}
            @if($reservation->status == 'completed' && $reservation->payments()->where('status', 'paid')->exists())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Menu Reviews</h3>
                    <div class="space-y-4">
                        @foreach($reservation->orderItems->groupBy('menu_id') as $menuItems)
                        @php 
                            $item = $menuItems->first(); 
                            $userReview = \App\Models\Review::getUserReviewForMenu(
                                auth()->id(), 
                                $item->menu_id, 
                                null, 
                                $reservation->id
                            );
                        @endphp
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                @if($item->menu->image_url)
                                    <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                        alt="{{ $item->menu->name }}"
                                        class="w-12 h-12 object-cover rounded mr-4"
                                        onerror="this.style.display='none'">
                                @endif
                                <div>
                                    <span class="font-medium">{{ $item->menu->name }}</span>
                                    <p class="text-sm text-gray-600">Quantity: {{ $menuItems->sum('quantity') }}</p>
                                </div>
                            </div>
                            
                            <div>
                                @if(!$userReview)
                                    <a href="{{ route('customer.reviews.create-from-reservation', ['reservation' => $reservation, 'menu' => $item->menu]) }}" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm transition-colors">
                                        ✩ Write Review
                                    </a>
                                @else
                                    <div class="flex items-center gap-3">
                                        <div class="text-center">
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                ✓ Reviewed
                                            </span>
                                            <div class="flex items-center justify-center mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-sm {{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                                @endfor
                                                <span class="text-xs text-gray-600 ml-1">({{ $userReview->rating }}/5)</span>
                                            </div>
                                        </div>
                                        <form action="{{ route('customer.reviews.destroy', $userReview) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm hover:bg-red-200 transition-colors"
                                                    onclick="return confirm('Are you sure you want to delete your review?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment History --}}
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

            {{-- Action Buttons --}}
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
        </div>
    </div>

    {{-- Include Add Menu Modal --}}
    @include('customer.reservation.partials.add-menu-modal', ['reservation' => $reservation, 'menus' => $menus])

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity management dengan konfirmasi dua langkah
        const decreaseButtons = document.querySelectorAll('.decrease-quantity');
        const increaseButtons = document.querySelectorAll('.increase-quantity');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        const changesSection = document.getElementById('changes-section');
        const confirmChangesBtn = document.getElementById('confirm-changes');
        const cancelChangesBtn = document.getElementById('cancel-changes');
        const changesMessage = document.getElementById('changes-message');

        let pendingChanges = new Map();
        let originalValues = new Map();
        let isProcessingOrder = {{ $reservation->hasProcessingOrder() ? 'true' : 'false' }};

        // Store original values
        quantityInputs.forEach(input => {
            const itemId = input.getAttribute('data-item-id');
            const originalValue = input.getAttribute('data-original-value');
            originalValues.set(itemId, parseInt(originalValue));
            input.setAttribute('data-original-value', originalValue);
        });

        // Decrease quantity dengan konfirmasi
        decreaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const canReduce = this.getAttribute('data-can-reduce') === 'true';
                if (!canReduce) {
                    showNotification('Cannot reduce items when order is processing. You can only add items.', 'info');
                    return;
                }

                const itemId = this.getAttribute('data-item-id');
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                let currentValue = parseInt(input.value);
                
                if (currentValue > 1) {
                    // Konfirmasi dua langkah untuk pengurangan
                    if (isProcessingOrder && currentValue > 1) {
                        showTwoStepConfirmation(
                            'Decrease Quantity',
                            `Are you sure you want to decrease quantity from ${currentValue} to ${currentValue - 1}?`,
                            () => {
                                currentValue--;
                                input.value = currentValue;
                                updatePendingChange(itemId, currentValue);
                                updateItemTotal(itemId, currentValue);
                                showChangesSection();
                            }
                        );
                    } else {
                        currentValue--;
                        input.value = currentValue;
                        updatePendingChange(itemId, currentValue);
                        updateItemTotal(itemId, currentValue);
                        showChangesSection();
                    }
                }
            });
        });

        // Increase quantity
        increaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                let currentValue = parseInt(input.value);
                
                // Konfirmasi dua langkah untuk penambahan pada order processing
                if (isProcessingOrder) {
                    showTwoStepConfirmation(
                        'Increase Quantity',
                        `Are you sure you want to increase quantity from ${currentValue} to ${currentValue + 1}?`,
                        () => {
                            currentValue++;
                            input.value = currentValue;
                            updatePendingChange(itemId, currentValue);
                            updateItemTotal(itemId, currentValue);
                            showChangesSection();
                        }
                    );
                } else {
                    currentValue++;
                    input.value = currentValue;
                    updatePendingChange(itemId, currentValue);
                    updateItemTotal(itemId, currentValue);
                    showChangesSection();
                }
            });
        });

        // Input change dengan konfirmasi
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const itemId = this.getAttribute('data-item-id');
                const canReduce = !this.readOnly;
                let value = parseInt(this.value);
                
                if (value < 1 || isNaN(value)) {
                    value = 1;
                    this.value = 1;
                }
                
                const originalValue = originalValues.get(itemId);
                
                // Konfirmasi dua langkah untuk perubahan pada order processing
                if (isProcessingOrder && value !== originalValue) {
                    const action = value > originalValue ? 'increase' : 'decrease';
                    showTwoStepConfirmation(
                        `${action === 'increase' ? 'Increase' : 'Decrease'} Quantity`,
                        `Are you sure you want to ${action} quantity from ${originalValue} to ${value}?`,
                        () => {
                            if (value < originalValue && !canReduce) {
                                showNotification('Cannot reduce items when order is processing. You can only add items.', 'info');
                                this.value = originalValue;
                                return;
                            }
                            
                            updatePendingChange(itemId, value);
                            updateItemTotal(itemId, value);
                            showChangesSection();
                        },
                        () => {
                            // Cancel callback - reset ke nilai original
                            this.value = originalValue;
                            updateItemTotal(itemId, originalValue);
                        }
                    );
                } else {
                    if (value < originalValue && !canReduce) {
                        showNotification('Cannot reduce items when order is processing. You can only add items.', 'info');
                        this.value = originalValue;
                        return;
                    }
                    
                    updatePendingChange(itemId, value);
                    updateItemTotal(itemId, value);
                    showChangesSection();
                }
            });

            input.addEventListener('input', function() {
                const itemId = this.getAttribute('data-item-id');
                let value = parseInt(this.value);
                
                if (!isNaN(value) && value >= 1) {
                    updateItemTotal(itemId, value);
                }
            });
        });

        // Fungsi konfirmasi dua langkah dengan dialog
        function showTwoStepConfirmation(title, message, confirmCallback, cancelCallback = null) {
            // Step 1: Basic confirmation
            const firstConfirmation = confirm(`${title}\n\n${message}\n\nClick OK to continue.`);
            
            if (!firstConfirmation) {
                if (cancelCallback) cancelCallback();
                return;
            }

            // Step 2: Detailed confirmation dengan custom dialog
            showCustomConfirmationDialog(title, message, confirmCallback, cancelCallback);
        }

        // Custom confirmation dialog
        function showCustomConfirmationDialog(title, message, confirmCallback, cancelCallback = null) {
            const dialog = document.createElement('div');
            dialog.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            dialog.innerHTML = `
                <div class="bg-white rounded-lg w-96 max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-2 text-gray-900">${title}</h3>
                    <p class="text-gray-600 mb-6">${message}</p>
                    
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-yellow-800">Order is being processed</span>
                        </div>
                        <p class="text-xs text-yellow-700 mt-1">Changes may affect kitchen preparation</p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors font-medium cancel-confirm-btn">
                            Cancel
                        </button>
                        <button type="button" 
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors font-medium confirm-btn">
                            Confirm Change
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(dialog);

            // Handle confirm button
            dialog.querySelector('.confirm-btn').addEventListener('click', function() {
                document.body.removeChild(dialog);
                confirmCallback();
                showNotification('Change confirmed and will be saved', 'success');
            });

            // Handle cancel button
            dialog.querySelector('.cancel-confirm-btn').addEventListener('click', function() {
                document.body.removeChild(dialog);
                if (cancelCallback) cancelCallback();
                showNotification('Change cancelled', 'info');
            });

            // Handle click outside
            dialog.addEventListener('click', function(e) {
                if (e.target === dialog) {
                    document.body.removeChild(dialog);
                    if (cancelCallback) cancelCallback();
                    showNotification('Change cancelled', 'info');
                }
            });
        }

        // Show changes section
        function showChangesSection() {
            if (pendingChanges.size > 0 && changesSection) {
                changesSection.classList.remove('hidden');
                const changedItems = pendingChanges.size;
                changesMessage.textContent = `${changedItems} item${changedItems > 1 ? 's' : ''} have pending changes`;
            }
        }

        // Hide changes section
        function hideChangesSection() {
            if (changesSection) {
                changesSection.classList.add('hidden');
            }
        }

        // Update pending changes
        function updatePendingChange(itemId, quantity) {
            const originalValue = originalValues.get(itemId);
            if (parseInt(quantity) !== parseInt(originalValue)) {
                pendingChanges.set(itemId, quantity);
            } else {
                pendingChanges.delete(itemId);
            }
            
            if (pendingChanges.size === 0) {
                hideChangesSection();
            } else {
                showChangesSection();
            }
        }

        // Update item total display
        function updateItemTotal(itemId, quantity) {
            const price = parseFloat(document.querySelector(`.quantity-input[data-item-id="${itemId}"]`).getAttribute('data-price'));
            const total = price * quantity;
            const totalElement = document.querySelector(`.item-total[data-item-id="${itemId}"]`);
            if (totalElement) {
                totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
            updateMenuTotal();
        }

        // Update menu total
        function updateMenuTotal() {
            let total = 0;
            quantityInputs.forEach(input => {
                const itemId = input.getAttribute('data-item-id');
                const price = parseFloat(input.getAttribute('data-price'));
                const quantity = parseInt(input.value);
                total += price * quantity;
            });
            
            const menuTotalElement = document.getElementById('menu-total');
            if (menuTotalElement) {
                menuTotalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        // Cancel changes
        if (cancelChangesBtn) {
            cancelChangesBtn.addEventListener('click', function() {
                quantityInputs.forEach(input => {
                    const itemId = input.getAttribute('data-item-id');
                    const originalValue = originalValues.get(itemId);
                    input.value = originalValue;
                    updateItemTotal(itemId, originalValue);
                });
                
                pendingChanges.clear();
                hideChangesSection();
                showNotification('Changes cancelled', 'info');
            });
        }
            
        // Confirm changes dengan konfirmasi dua langkah
        if (confirmChangesBtn) {
            confirmChangesBtn.addEventListener('click', async function() {
                if (pendingChanges.size === 0) {
                    showNotification('No changes to confirm', 'info');
                    return;
                }

                // Step 1: Basic confirmation
                const changeSummary = Array.from(pendingChanges.entries())
                    .map(([itemId, quantity]) => {
                        const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                        const menuName = input ? input.closest('.flex-col').querySelector('h4').textContent : 'Item';
                        const originalValue = originalValues.get(itemId);
                        return `• ${menuName}: ${originalValue} → ${quantity}`;
                    })
                    .join('\n');

                const firstConfirmation = confirm(
                    `Please confirm your changes:\n\n${changeSummary}\n\nClick OK to continue.`
                );

                if (!firstConfirmation) {
                    return;
                }

                // Step 2: Detailed confirmation
                showFinalConfirmationDialog(changeSummary);
            });
        }

        // Final confirmation dialog
        function showFinalConfirmationDialog(changeSummary) {
            const dialog = document.createElement('div');
            dialog.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            dialog.innerHTML = `
                <div class="bg-white rounded-lg w-96 max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Final Confirmation</h3>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-medium text-blue-800">Changes Summary</span>
                        </div>
                        <div class="text-sm text-blue-700 whitespace-pre-line">${changeSummary}</div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium text-yellow-800">Important Notice</span>
                        </div>
                        <p class="text-xs text-yellow-700 mt-1">
                            ${isProcessingOrder ? 
                                'Your order is currently being prepared. Changes may affect preparation time.' : 
                                'Please review your changes carefully before confirming.'}
                        </p>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors font-medium cancel-final-btn">
                            Cancel
                        </button>
                        <button type="button" 
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors font-medium confirm-final-btn">
                            Save All Changes
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(dialog);

            // Handle final confirm button
            dialog.querySelector('.confirm-final-btn').addEventListener('click', function() {
                document.body.removeChild(dialog);
                processChanges();
            });

            // Handle final cancel button
            dialog.querySelector('.cancel-final-btn').addEventListener('click', function() {
                document.body.removeChild(dialog);
                showNotification('Changes cancelled', 'info');
            });

            // Handle click outside
            dialog.addEventListener('click', function(e) {
                if (e.target === dialog) {
                    document.body.removeChild(dialog);
                    showNotification('Changes cancelled', 'info');
                }
            });
        }

        // Process changes setelah konfirmasi final
        async function processChanges() {
            const originalText = confirmChangesBtn.textContent;
            confirmChangesBtn.textContent = 'Saving...';
            confirmChangesBtn.disabled = true;

            try {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('customer.reservations.menu.update-items', $reservation) }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                
                let updateIndex = 0;
                for (const [itemId, quantity] of pendingChanges.entries()) {
                    const itemIdField = document.createElement('input');
                    itemIdField.type = 'hidden';
                    itemIdField.name = `updates[${updateIndex}][itemId]`;
                    itemIdField.value = itemId;
                    
                    const quantityField = document.createElement('input');
                    quantityField.type = 'hidden';
                    quantityField.name = `updates[${updateIndex}][quantity]`;
                    quantityField.value = quantity;
                    
                    form.appendChild(itemIdField);
                    form.appendChild(quantityField);
                    updateIndex++;
                }
                
                document.body.appendChild(form);
                
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken.value
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                document.body.removeChild(form);
                
                if (result.success) {
                    pendingChanges.clear();
                    hideChangesSection();
                    
                    // Update original values
                    quantityInputs.forEach(input => {
                        const itemId = input.getAttribute('data-item-id');
                        const currentValue = parseInt(input.value);
                        input.setAttribute('data-original-value', currentValue);
                        originalValues.set(itemId, currentValue);
                    });
                    
                    showNotification('✓ All changes saved successfully!', 'success');
                    
                    // Reload page after 1.5 seconds to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                                
                } else {
                    throw new Error(result.message || 'Failed to save changes');
                }

            } catch (error) {
                console.error('Error:', error);
                showNotification('✗ Error saving changes: ' + error.message, 'error');
            } finally {
                confirmChangesBtn.textContent = originalText;
                confirmChangesBtn.disabled = false;
            }
        }

        // Initialize session notifications
        function initializeSessionNotifications() {
            const sessionNotifications = document.querySelectorAll('.session-notification');
            
            sessionNotifications.forEach(notification => {
                const autoClose = notification.getAttribute('data-auto-close') === 'true';
                const closeButton = notification.querySelector('.close-session-notification');
                
                if (closeButton) {
                    closeButton.addEventListener('click', function() {
                        animateNotificationOut(notification);
                    });
                }
                
                if (autoClose) {
                    setTimeout(() => {
                        if (notification.parentNode) {
                            animateNotificationOut(notification);
                        }
                    }, 5000);
                }
            });
        }

        function animateNotificationOut(notification) {
            notification.style.transition = 'all 0.3s ease-out';
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            notification.style.maxHeight = '0';
            notification.style.marginBottom = '0';
            notification.style.paddingTop = '0';
            notification.style.paddingBottom = '0';
            notification.style.overflow = 'hidden';
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }

        initializeSessionNotifications();
    });

    function showNotification(message, type = 'info') {
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => {
            if (notification.parentNode) {
                notification.remove();
            }
        });
        
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 left-1/2 transform -translate-x-1/2 z-50 px-4 py-3 rounded-lg shadow-lg border-l-4 max-w-md w-full mx-4`;
        
        let bgColor, borderColor, textColor, icon;
        switch(type) {
            case 'success':
                bgColor = 'bg-green-50';
                borderColor = 'border-green-400';
                textColor = 'text-green-700';
                icon = '✓';
                break;
            case 'error':
                bgColor = 'bg-red-50';
                borderColor = 'border-red-400';
                textColor = 'text-red-700';
                icon = '✗';
                break;
            default:
                bgColor = 'bg-blue-50';
                borderColor = 'border-blue-400';
                textColor = 'text-blue-700';
                icon = 'ℹ';
        }
        
        notification.className += ` ${bgColor} ${borderColor} ${textColor}`;
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="font-semibold mr-2">${icon}</span>
                    <span class="text-sm font-medium">${message}</span>
                </div>
                <button type="button" class="close-notification text-gray-400 hover:text-gray-600 ml-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        notification.style.opacity = '0';
        notification.style.transform = 'translate(-50%, -20px)';
        
        setTimeout(() => {
            notification.style.transition = 'all 0.3s ease-out';
            notification.style.opacity = '1';
            notification.style.transform = 'translate(-50%, 0)';
        }, 10);
        
        const closeBtn = notification.querySelector('.close-notification');
        closeBtn.addEventListener('click', () => {
            animateNotificationOut(notification);
        });
        
        setTimeout(() => {
            if (notification.parentNode) {
                animateNotificationOut(notification);
            }
        }, 4000);
    }

    function openAddMenuModal() {
        const modal = document.getElementById('addMenuModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAddMenuModal() {
        const modal = document.getElementById('addMenuModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
</script>
</x-app-layout>