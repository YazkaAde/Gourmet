<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Order') }} #{{ $order->id }}
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

            @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Order Status Alert -->
                    <div class="mb-6 p-4 rounded-lg 
                        @if($order->status == 'pending') bg-blue-50 border border-blue-200
                        @elseif($order->status == 'processing') bg-yellow-50 border border-yellow-200
                        @else bg-gray-50 border border-gray-200 @endif">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 
                                @if($order->status == 'pending') text-blue-600
                                @elseif($order->status == 'processing') text-yellow-600
                                @else text-gray-600 @endif" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium 
                                    @if($order->status == 'pending') text-blue-800
                                    @elseif($order->status == 'processing') text-yellow-800
                                    @else text-gray-800 @endif">
                                    @if($order->status == 'pending')
                                        Order is pending. You can freely edit, add, or remove items.
                                    @elseif($order->status == 'processing')
                                        Order is being processed. You can only increase quantities or add new items.
                                    @else
                                        Order cannot be edited as it's already {{ $order->status }}.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('customer.orders.update', $order) }}" method="POST" id="orderForm">
                        @csrf
                        @method('PUT')

                        <!-- Order Type Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Type</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 
                                    {{ $order->order_type == 'dine_in' ? 'border-primary-500 bg-primary-50' : 'border-gray-300' }}">
                                    <input type="radio" name="order_type" value="dine_in" 
                                        class="text-primary-600 focus:ring-primary-500" 
                                        {{ $order->order_type == 'dine_in' ? 'checked' : '' }}
                                        {{ $order->status == 'completed' || $order->status == 'cancelled' ? 'disabled' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Dine In</span>
                                        <span class="block text-sm text-gray-500">Eat at the restaurant</span>
                                    </div>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 
                                    {{ $order->order_type == 'take_away' ? 'border-primary-500 bg-primary-50' : 'border-gray-300' }}">
                                    <input type="radio" name="order_type" value="take_away" 
                                        class="text-primary-600 focus:ring-primary-500" 
                                        {{ $order->order_type == 'take_away' ? 'checked' : '' }}
                                        {{ $order->status == 'completed' || $order->status == 'cancelled' ? 'disabled' : '' }}>
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Take Away</span>
                                        <span class="block text-sm text-gray-500">Take food to go</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Table Number -->
                        <div id="tableNumberSection" class="mb-6 
                            {{ $order->order_type != 'dine_in' ? 'hidden' : '' }}">
                            <label for="table_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Table Number
                            </label>
                            <select name="table_number" id="table_number" 
                                class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 w-full
                                {{ $order->status != 'pending' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $order->status != 'pending' ? 'disabled' : '' }}>
                                <option value="">Select Table</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table->table_number }}" 
                                        {{ $order->table_number == $table->table_number ? 'selected' : '' }}>
                                        Table {{ $table->table_number }} (Capacity: {{ $table->table_capacity }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Only applicable for Dine In orders</p>
                        </div>

                        <!-- Order Items -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-medium text-gray-700">Order Items</label>
                                @if($order->status == 'processing')
                                <div class="text-sm text-orange-600 font-medium">
                                    ⚠️ Cannot reduce quantity of existing items
                                </div>
                                @endif
                            </div>
                            
                            <div class="space-y-4" id="order-items-container">
                                @foreach($order->orderItems as $orderItem)
                                <div class="flex items-center justify-between p-4 border rounded-lg bg-white" data-item-id="{{ $orderItem->id }}">
                                    <div class="flex items-center space-x-4 flex-1">
                                        @if($orderItem->menu->image_url)
                                            <img src="{{ asset('storage/' . $orderItem->menu->image_url) }}" 
                                                alt="{{ $orderItem->menu->name }}"
                                                class="w-16 h-16 object-cover rounded"
                                                onerror="this.style.display='none'">
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $orderItem->menu->name }}</h4>
                                            <p class="text-sm text-gray-600">Rp {{ number_format($orderItem->price, 0) }} each</p>
                                            <p class="text-sm font-semibold text-primary-600">
                                                Total: Rp {{ number_format($orderItem->total_price, 0) }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-3">
                                        @if($order->status == 'pending')
                                        <!-- Remove button for pending orders -->
                                        <button type="button" 
                                                onclick="removeItem('{{ $orderItem->id }}')"
                                                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-50 transition-colors"
                                                title="Remove item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        @endif
                                        
                                        <div class="flex items-center space-x-2">
                                            <button type="button" 
                                                    onclick="updateQuantity('{{ $orderItem->id }}', -1)"
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors
                                                    {{ $order->status == 'processing' && $orderItem->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $order->status == 'processing' && $orderItem->quantity <= 1 ? 'disabled' : '' }}>
                                                -
                                            </button>
                                            
                                            <input type="number" 
                                                   name="order_items[{{ $orderItem->id }}][quantity]" 
                                                   id="quantity_{{ $orderItem->id }}"
                                                   value="{{ $orderItem->quantity }}"
                                                   min="{{ $order->status == 'processing' ? $orderItem->quantity : 1 }}"
                                                   max="10"
                                                   class="w-16 text-center border-gray-300 rounded-lg focus:border-primary-500 focus:ring-primary-500"
                                                   onchange="validateQuantity('{{ $orderItem->id }}', this.value, {{ $orderItem->quantity }})">
                                                   
                                            <input type="hidden" 
                                                   name="order_items[{{ $orderItem->id }}][id]" 
                                                   value="{{ $orderItem->id }}">
                                                   
                                            <button type="button" 
                                                    onclick="updateQuantity('{{ $orderItem->id }}', 1)"
                                                    class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-100 transition-colors">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div id="items-to-remove-container"></div>
                        </div>

                        <!-- Special Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Special Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 w-full
                                {{ $order->status != 'pending' ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                                {{ $order->status != 'pending' ? 'disabled' : '' }}
                                placeholder="Any special requests or instructions...">{{ old('notes', $order->notes) }}</textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                            <a href="{{ route('customer.orders.show', $order) }}" 
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </a>
                            
                            <div class="flex space-x-3">
                                @if($order->status == 'pending')
                                <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                                            onclick="return confirm('Are you sure you want to cancel this order?')">
                                        Cancel Order
                                    </button>
                                </form>
                                @endif

                                <button type="submit" 
                                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium
                                        {{ $order->status == 'completed' || $order->status == 'cancelled' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $order->status == 'completed' || $order->status == 'cancelled' ? 'disabled' : '' }}>
                                    Update Order
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="order_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const tableSection = document.getElementById('tableNumberSection');
                if (this.value === 'dine_in') {
                    tableSection.classList.remove('hidden');
                } else {
                    tableSection.classList.add('hidden');
                }
            });
        });

        let itemsToRemove = [];

        function removeItem(itemId) {
            if (confirm('Are you sure you want to remove this item?')) {
                itemsToRemove.push(itemId);
                updateRemovalInputs();
                
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.style.display = 'none';
                }
            }
        }

        function updateRemovalInputs() {
            const container = document.getElementById('items-to-remove-container');
            container.innerHTML = '';
            
            itemsToRemove.forEach(itemId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items_to_remove[]';
                input.value = itemId;
                container.appendChild(input);
            });
        }

        function validateQuantity(itemId, newValue, minQuantity) {
            const input = document.getElementById(`quantity_${itemId}`);
            const parsedValue = parseInt(newValue);
            
            if (isNaN(parsedValue) || parsedValue < 1) {
                input.value = minQuantity;
                return;
            }
            
            if (parsedValue > 10) {
                input.value = 10;
                alert('Maximum quantity per item is 10');
                return;
            }
            
            if (parsedValue < minQuantity) {
                input.value = minQuantity;
                alert('Cannot reduce quantity below ' + minQuantity + ' for items that are being processed.');
                return;
            }
            
            input.value = parsedValue;
        }

        function updateQuantity(itemId, change) {
            const input = document.getElementById(`quantity_${itemId}`);
            const currentValue = parseInt(input.value);
            const minQuantity = parseInt(input.min);
            const newValue = currentValue + change;
            
            if (newValue >= minQuantity && newValue <= 10) {
                input.value = newValue;
            } else if (newValue < minQuantity) {
                alert('Cannot reduce quantity below ' + minQuantity + ' for items that are being processed.');
            } else if (newValue > 10) {
                alert('Maximum quantity per item is 10');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const selectedOrderType = document.querySelector('input[name="order_type"]:checked');
            if (selectedOrderType) {
                const tableSection = document.getElementById('tableNumberSection');
                if (selectedOrderType.value === 'dine_in') {
                    tableSection.classList.remove('hidden');
                } else {
                    tableSection.classList.add('hidden');
                }
            }
            
            updateRemovalInputs();
        });
    </script>
</x-app-layout>