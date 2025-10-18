<x-app-layout x-data="{ openCart: true }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($carts->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                    </svg>
                    <p class="text-gray-600 mb-4">Your cart is empty</p>
                    <a href="{{ route('customer.menu.index') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 transition">
                        Browse Menu
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Selection Controls -->
                        <div class="flex items-center justify-between mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-gray-700">Select All</span>
                                </label>
                                <button type="button" id="remove-selected" class="text-red-600 hover:text-red-800 text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                                    Remove Selected
                                </button>
                            </div>
                            <div class="text-sm text-gray-600">
                                <span id="selected-count">0</span> items selected
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($carts as $cart)
                                <div class="flex p-4 border rounded-lg bg-white hover:shadow-md transition-shadow cart-item" data-cart-id="{{ $cart->id }}">
                                    <!-- Checkbox untuk memilih item -->
                                    <div class="flex items-start mr-4">
                                        <input type="checkbox" 
                                               class="item-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                               value="{{ $cart->id }}"
                                               data-price="{{ $cart->menu->price * $cart->quantity }}">
                                    </div>
                                    
                                    <!-- Gambar Menu -->
                                    @if($cart->menu->image_url)
                                    <div class="flex-shrink-0 w-24 h-24 mr-4">
                                        <img 
                                            src="{{ asset('storage/' . $cart->menu->image_url) }}" 
                                            alt="{{ $cart->menu->name }}"
                                            class="w-full h-full object-cover rounded-lg"
                                            onerror="this.style.display='none'">
                                    </div>
                                    @endif
                                    
                                    <div class="flex-grow flex flex-col justify-between">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">{{ $cart->menu->name }}</h3>
                                            <p class="text-gray-600">Rp {{ number_format($cart->menu->price, 0) }}</p>
                                        </div>
                                        
                                        <!-- Quantity Update Form -->
                                        <form action="{{ route('customer.cart.update', $cart->id) }}" method="POST" class="flex items-center gap-2 mt-2" id="form-{{ $cart->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                                <button type="button"
                                                        class="quantity-decrement px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold disabled:opacity-40 disabled:cursor-not-allowed"
                                                        aria-label="Decrease quantity"
                                                        data-cart-id="{{ $cart->id }}">
                                                    −
                                                </button>

                                                <input type="number" 
                                                       name="quantity" 
                                                       value="{{ $cart->quantity }}" 
                                                       min="1" 
                                                       class="quantity-input w-16 text-center border-x border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 py-1"
                                                       data-cart-id="{{ $cart->id }}"
                                                       data-price="{{ $cart->menu->price }}">

                                                <button type="button"
                                                        class="quantity-increment px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold"
                                                        aria-label="Increase quantity"
                                                        data-cart-id="{{ $cart->id }}">
                                                    +
                                                </button>
                                            </div>
                                        </form>
                                        
                                        <p class="mt-2 item-total text-gray-800 font-medium" data-cart-id="{{ $cart->id }}">
                                            Total: Rp {{ number_format($cart->menu->price * $cart->quantity, 0) }}
                                        </p>
                                    </div>
                                    
                                    <!-- Tombol Remove dengan Icon Tempat Sampah -->
                                    <form action="{{ route('customer.cart.destroy', $cart->id) }}" method="POST" class="flex items-start">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Are you sure you want to remove this item from your cart?')"
                                                aria-label="Remove item">
                                            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Total dan Checkout -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <div class="text-xl font-bold text-gray-900" id="cart-total">
                                        Total: Rp {{ number_format($carts->sum(function($cart) { return $cart->menu->price * $cart->quantity; }), 0) }}
                                    </div>
                                    <div class="text-lg font-semibold text-primary-600" id="selected-total">
                                        Selected: Rp 0
                                    </div>
                                </div>
                                <button class="checkout-btn bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 font-semibold transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                    </svg>
                                    Checkout Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedItems = new Set();
            
            // Fungsi untuk mengupdate selection UI
            function updateSelectionUI() {
                const selectedCount = selectedItems.size;
                const totalItems = document.querySelectorAll('.item-checkbox').length;
                
                // Update selected count
                document.getElementById('selected-count').textContent = selectedCount;
                
                // Update select all checkbox
                const selectAll = document.getElementById('select-all');
                if (selectedCount === 0) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                } else if (selectedCount === totalItems) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                } else {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                }
                
                // Update remove selected button
                const removeSelectedBtn = document.getElementById('remove-selected');
                removeSelectedBtn.disabled = selectedCount === 0;
                
                // Update checkout button
                const checkoutBtn = document.querySelector('.checkout-btn');
                checkoutBtn.disabled = selectedCount === 0;
                
                // Update selected total
                updateSelectedTotal();
            }
            
            // Fungsi untuk mengupdate total item yang dipilih
            function updateSelectedTotal() {
                let selectedTotal = 0;
                
                document.querySelectorAll('.item-checkbox:checked').forEach(function(checkbox) {
                    selectedTotal += parseFloat(checkbox.getAttribute('data-price')) || 0;
                });
                
                document.getElementById('selected-total').textContent = `Selected: Rp ${selectedTotal.toLocaleString()}`;
            }
            
            // Handle select all checkbox
            document.getElementById('select-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    if (this.checked) {
                        selectedItems.add(checkbox.value);
                    } else {
                        selectedItems.delete(checkbox.value);
                    }
                });
                updateSelectionUI();
            });
            
            // Handle individual item checkboxes
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('item-checkbox')) {
                    if (e.target.checked) {
                        selectedItems.add(e.target.value);
                    } else {
                        selectedItems.delete(e.target.value);
                    }
                    updateSelectionUI();
                }
            });
            
            // Handle remove selected button
            document.getElementById('remove-selected').addEventListener('click', function() {
                if (selectedItems.size === 0) return;
                
                if (!confirm(`Are you sure you want to remove ${selectedItems.size} item(s) from your cart?`)) {
                    return;
                }
                
                fetch('{{ route("customer.cart.destroy-multiple") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        cart_ids: Array.from(selectedItems)
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove selected items from DOM
                        selectedItems.forEach(cartId => {
                            const item = document.querySelector(`.cart-item[data-cart-id="${cartId}"]`);
                            if (item) {
                                item.remove();
                            }
                        });
                        
                        selectedItems.clear();
                        updateSelectionUI();
                        updateTotals();
                        
                        // Check if cart is empty
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            window.location.reload();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error removing items:', error);
                    alert('Error removing selected items');
                });
            });

            // Fungsi untuk mengupdate kontrol kuantitas
            function updateQuantityControl(input) {
                const cartId = input.getAttribute('data-cart-id');
                const min = parseInt(input.getAttribute('min')) || 1;
                const currentValue = parseInt(input.value) || min;
                const decrementBtn = document.querySelector(`.quantity-decrement[data-cart-id="${cartId}"]`);
                
                if (isNaN(currentValue) || currentValue < min) {
                    input.value = min;
                }
                
                if (decrementBtn) {
                    decrementBtn.disabled = parseInt(input.value) <= min;
                }
            }
            
            // Fungsi untuk mengupdate total per item dan total keseluruhan
            function updateTotals() {
                let grandTotal = 0;
                
                document.querySelectorAll('.quantity-input').forEach(function(input) {
                    const cartId = input.getAttribute('data-cart-id');
                    const price = parseInt(input.getAttribute('data-price'));
                    const quantity = parseInt(input.value) || 1;
                    const itemTotal = price * quantity;
                    
                    // Update total per item
                    const itemTotalElement = document.querySelector(`.item-total[data-cart-id="${cartId}"]`);
                    if (itemTotalElement) {
                        itemTotalElement.textContent = `Total: Rp ${itemTotal.toLocaleString()}`;
                    }
                    
                    // Update data-price attribute on checkbox
                    const checkbox = document.querySelector(`.item-checkbox[value="${cartId}"]`);
                    if (checkbox) {
                        checkbox.setAttribute('data-price', itemTotal);
                    }
                    
                    grandTotal += itemTotal;
                });
                
                // Update total keseluruhan
                const cartTotalElement = document.getElementById('cart-total');
                if (cartTotalElement) {
                    cartTotalElement.textContent = `Total: Rp ${grandTotal.toLocaleString()}`;
                }
                
                // Update selected total jika ada item yang dipilih
                if (selectedItems.size > 0) {
                    updateSelectedTotal();
                }
                
                return grandTotal;
            }
            
            // Fungsi untuk mengirim form update
            function submitUpdateForm(cartId) {
                const form = document.getElementById(`form-${cartId}`);
                if (form) {
                    // Buat request AJAX
                    fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log('Quantity updated successfully');
                            // Update checkbox data-price attribute
                            const checkbox = document.querySelector(`.item-checkbox[value="${cartId}"]`);
                            if (checkbox && checkbox.checked) {
                                checkbox.setAttribute('data-price', data.new_total);
                                updateSelectedTotal();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error updating quantity:', error);
                    });
                }
            }
            
            // Handle checkout button
            const checkoutBtn = document.querySelector('.checkout-btn');
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', async function() {
                    if (selectedItems.size === 0) return;
                    
                    try {
                        // Ambil daftar meja dari server
                        const response = await fetch('{{ route("api.available-tables") }}');
                        const tables = await response.json();
                        
                        if (tables.length === 0) {
                            alert('No tables available at the moment.');
                            return;
                        }
                        
                        // Buat form untuk memilih meja
                        let tableOptions = '';
                        tables.forEach(table => {
                            tableOptions += `<option value="${table.table_number}">Table ${table.table_number} (Capacity: ${table.table_capacity})</option>`;
                        });
                        
                        const tableForm = `
                            <div class="p-4">
                                <h3 class="text-lg font-medium mb-3">Checkout ${selectedItems.size} Selected Item(s)</h3>
                                <p class="text-sm text-gray-600 mb-3">Select table for your order:</p>
                                <select id="table-select" class="w-full p-2 border border-gray-300 rounded mb-3">
                                    ${tableOptions}
                                </select>
                                <div class="flex justify-end gap-2">
                                    <button type="button" id="cancel-table" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                                    <button type="button" id="confirm-table" class="px-4 py-2 bg-primary-600 text-white rounded">Confirm Checkout</button>
                                </div>
                            </div>
                        `;
                        
                        // Tampilkan modal
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        modal.innerHTML = `
                            <div class="bg-white rounded-lg w-96">
                                ${tableForm}
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        // Handle confirm button
                        document.getElementById('confirm-table').addEventListener('click', function() {
                            const selectedTable = document.getElementById('table-select').value;
                            modal.remove();
                            
                            checkoutBtn.disabled = true;
                            checkoutBtn.innerHTML = `
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            `;
                            
                            fetch('{{ route("customer.cart.checkout") }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    table_number: selectedTable,
                                    selected_cart_ids: Array.from(selectedItems)
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Order created successfully! Your order ID: ' + data.order_id);
                                    window.location.href = '{{ route("customer.orders.index") }}';
                                } else {
                                    alert('Error: ' + data.message);
                                    checkoutBtn.disabled = false;
                                    checkoutBtn.innerHTML = `
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                        </svg>
                                        Checkout Selected
                                    `;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred during checkout');
                                checkoutBtn.disabled = false;
                                checkoutBtn.innerHTML = `
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5"/>
                                    </svg>
                                    Checkout Selected
                                `;
                            });
                        });
                        
                        document.getElementById('cancel-table').addEventListener('click', function() {
                            modal.remove();
                        });
                        
                    } catch (error) {
                        console.error('Error fetching tables:', error);
                        alert('Error loading table information');
                    }
                });
            }
            
            document.addEventListener('click', function(e) {
                const incrementBtn = e.target.closest('.quantity-increment');
                const decrementBtn = e.target.closest('.quantity-decrement');
                
                if (incrementBtn) {
                    const cartId = incrementBtn.getAttribute('data-cart-id');
                    const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                    input.value = parseInt(input.value) + 1;
                    updateQuantityControl(input);
                    updateTotals();
                    submitUpdateForm(cartId);
                }
                
                if (decrementBtn && !decrementBtn.disabled) {
                    const cartId = decrementBtn.getAttribute('data-cart-id');
                    const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                    const min = parseInt(input.getAttribute('min')) || 1;
                    const currentValue = parseInt(input.value) || min;
                    
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                        updateQuantityControl(input);
                        updateTotals();
                        submitUpdateForm(cartId);
                    }
                }
            });
            
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const cartId = e.target.getAttribute('data-cart-id');
                    updateQuantityControl(e.target);
                    updateTotals();
                    
                    clearTimeout(window[`debounceTimeout_${cartId}`]);
                    window[`debounceTimeout_${cartId}`] = setTimeout(() => {
                        submitUpdateForm(cartId);
                    }, 800);
                }
            });
            
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const cartId = e.target.getAttribute('data-cart-id');
                    updateQuantityControl(e.target);
                    updateTotals();
                    submitUpdateForm(cartId);
                }
            });
            
            document.querySelectorAll('.quantity-input').forEach(function(input) {
                updateQuantityControl(input);
            });
        });
    </script>
</x-app-layout>