<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reservation') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('customer.reservations.update', $reservation) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="reservation_date" class="block text-sm font-medium text-gray-700">Tanggal Reservasi</label>
                                <input type="date" name="reservation_date" id="reservation_date" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('reservation_date', $reservation->reservation_date->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d') }}" required>
                                @error('reservation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reservation_time" class="block text-sm font-medium text-gray-700">Waktu Reservasi</label>
                                <input type="time" name="reservation_time" id="reservation_time" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('reservation_time', $reservation->reservation_time) }}"
                                    required>
                                @error('reservation_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Berakhir Reservasi</label>
                                <input type="time" name="end_time" id="end_time" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('end_time', $reservation->end_time) }}"
                                    required>
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Minimal 1 jam dari waktu mulai</p>
                            </div>

                            <div>
                                <label for="guest_count" class="block text-sm font-medium text-gray-700">Jumlah Tamu</label>
                                <input type="number" name="guest_count" id="guest_count" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('guest_count', $reservation->guest_count) }}"
                                    min="1" max="100" required>
                                @error('guest_count')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="table_number" class="block text-sm font-medium text-gray-700">Pilih Meja</label>
                                <select name="table_number" id="table_number" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500" required>
                                    <option value="">Pilih Meja</option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table->table_number }}" 
                                            data-capacity="{{ $table->table_capacity }}"
                                            {{ old('table_number', $reservation->table_number) == $table->table_number ? 'selected' : '' }}>
                                            Meja {{ $table->table_number }} (Kapasitas: {{ $table->table_capacity }} orang)
                                        </option>
                                    @endforeach
                                </select>
                                @error('table_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $reservation->notes) }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Menu items section -->
                            <div class="md:col-span-2">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Pre-Order Menu Items</h3>
                                    <button type="button" 
                                            onclick="openAddMenuModal()"
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        + Add Menu Items
                                    </button>
                                </div>
                                
                                <div id="menu-items-container" class="space-y-4">
                                    @foreach($reservation->orderItems->groupBy('menu_id') as $menuItems)
                                        @php 
                                            $item = $menuItems->first(); 
                                            $totalQuantity = $menuItems->sum('quantity');
                                        @endphp
                                        <div class="menu-item border rounded-lg p-4">
                                            <div class="flex justify-between items-center">
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
                                                        <input type="hidden" name="menu_items[{{ $loop->index }}][menu_id]" value="{{ $item->menu->id }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-4">
                                                    <div class="flex items-center border rounded-lg overflow-hidden">
                                                        <input type="number" 
                                                            name="menu_items[{{ $loop->index }}][quantity]" 
                                                            value="{{ $totalQuantity }}" 
                                                            min="1" 
                                                            class="w-20 px-3 py-2 text-center border-0 quantity-input"
                                                            data-price="{{ $item->price }}">
                                                    </div>
                                                    
                                                    <button type="button" 
                                                            class="remove-menu-item px-3 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors">
                                                        Remove
                                                    </button>
                                                    
                                                    <p class="font-medium w-24 text-right item-total">
                                                        Rp {{ number_format($item->price * $totalQuantity, 0) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($reservation->orderItems->count() == 0)
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg">
                                    <p class="text-gray-500">No menu items added yet.</p>
                                    <p class="text-sm text-gray-400 mt-1">Click "Add Menu Items" to select from our menu</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Reservation Summary -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-md">
                            <h3 class="text-lg font-medium text-gray-900">Ringkasan Reservasi</h3>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <p class="text-sm text-gray-600">Biaya Reservasi:</p>
                                <p class="text-sm font-medium" id="reservation-fee">Rp 0</p>
                                
                                <p class="text-sm text-gray-600">Total Menu:</p>
                                <p class="text-sm font-medium" id="menu-total">Rp 0</p>
                                
                                <p class="text-sm text-gray-600">Total Amount:</p>
                                <p class="text-sm font-medium" id="total-amount">Rp 0</p>
                                
                                <p class="text-sm text-gray-600">DP (10%):</p>
                                <p class="text-sm font-medium" id="down-payment">Rp 0</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('customer.reservations.show', $reservation) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                Update Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Reservation Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Detail Reservasi Saat Ini</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-medium">{{ $reservation->reservation_date->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Waktu</p>
                            <p class="font-medium">{{ $reservation->reservation_time }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Waktu Berakhir</p>
                            <p class="font-medium">{{ $reservation->end_time }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jumlah Tamu</p>
                            <p class="font-medium">{{ $reservation->guest_count }} guests</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Meja</p>
                            <p class="font-medium">Meja {{ $reservation->table_number }} ({{ $reservation->table_capacity }} orang)</p>
                        </div>
                        @if($reservation->notes)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-600">Catatan</p>
                            <p class="font-medium">{{ $reservation->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('customer.reservation.partials.add-menu-modal', ['reservation' => $reservation, 'menus' => $menus])

    <script>
            document.addEventListener('DOMContentLoaded', function() {
        const menuItemsContainer = document.getElementById('menu-items-container');
        let menuItemIndex = {{ count(old('menu_items', [])) }};

        // Initialize menu items event listeners dengan event delegation
        function initializeMenuItems() {
            // Gunakan event delegation untuk menangani klik pada seluruh container
            menuItemsContainer.addEventListener('click', function(e) {
                // Handle tombol tambah
                if (e.target.classList.contains('increase-quantity')) {
                    const index = e.target.getAttribute('data-index');
                    const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
                    if (input) {
                        input.value = parseInt(input.value) + 1;
                        updateItemTotal(index);
                        calculateReservationFee();
                    }
                }
                
                // Handle tombol kurang
                if (e.target.classList.contains('decrease-quantity')) {
                    const index = e.target.getAttribute('data-index');
                    const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
                    if (input && parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateItemTotal(index);
                        calculateReservationFee();
                    }
                }
                
                // Handle tombol remove
                if (e.target.classList.contains('remove-menu-item')) {
                    const index = e.target.getAttribute('data-index');
                    const item = document.querySelector(`.menu-item[data-index="${index}"]`);
                    if (item) {
                        item.remove();
                        calculateReservationFee();
                        updateEmptyState();
                    }
                }
            });

            // Event listener untuk input quantity manual
            menuItemsContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const index = e.target.getAttribute('data-index');
                    updateItemTotal(index);
                    calculateReservationFee();
                }
            });
        }

        // Update item total display
        function updateItemTotal(index) {
            const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
            const price = parseFloat(input.getAttribute('data-price'));
            const quantity = parseInt(input.value);
            const total = price * quantity;
            const totalElement = document.querySelector(`.item-total[data-index="${index}"]`);
            if (totalElement) {
                totalElement.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }
        
        // Update empty state
        function updateEmptyState() {
            const menuItems = document.querySelectorAll('.menu-item');
            const emptyState = document.getElementById('empty-state');
            
            if (menuItems.length === 0 && !emptyState) {
                const emptyDiv = document.createElement('div');
                emptyDiv.id = 'empty-state';
                emptyDiv.className = 'text-center py-8 border-2 border-dashed border-gray-300 rounded-lg';
                emptyDiv.innerHTML = `
                    <p class="text-gray-500">No menu items added yet.</p>
                    <p class="text-sm text-gray-400 mt-1">Click "Add Menu Items" to select from our menu</p>
                `;
                menuItemsContainer.appendChild(emptyDiv);
            } else if (menuItems.length > 0 && emptyState) {
                emptyState.remove();
            }
        }

        // Function to add menu item from modal
        window.addMenuItemToForm = function(menuId, menuName, menuPrice, menuImage, quantity = 1) {
            const emptyState = document.getElementById('empty-state');
            if (emptyState) {
                emptyState.remove();
            }
            
            // Check if item already exists
            const existingInput = document.querySelector(`input[value="${menuId}"][name*="menu_id"]`);
            if (existingInput) {
                const existingIndex = existingInput.name.match(/\[(\d+)\]/)[1];
                const quantityInput = document.querySelector(`.quantity-input[data-index="${existingIndex}"]`);
                const currentQuantity = parseInt(quantityInput.value);
                quantityInput.value = currentQuantity + quantity;
                updateItemTotal(existingIndex);
                calculateReservationFee();
                showNotification('Menu item quantity updated!', 'success');
                return;
            }
            
            const index = menuItemIndex++;
            const menuItemHtml = `
                <div class="menu-item border rounded-lg p-4" data-index="${index}">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center flex-1">
                            ${menuImage ? `<img src="${menuImage}" alt="${menuName}" class="w-16 h-16 object-cover rounded mr-4" onerror="this.style.display='none'">` : ''}
                            <div class="flex-1">
                                <h4 class="font-semibold">${menuName}</h4>
                                <p class="text-sm text-gray-600">Rp ${parseInt(menuPrice).toLocaleString('id-ID')} per item</p>
                                <input type="hidden" name="menu_items[${index}][menu_id]" value="${menuId}">
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border rounded-lg overflow-hidden">
                                <button type="button" 
                                        class="px-3 py-2 bg-gray-200 hover:bg-gray-300 decrease-quantity transition-colors"
                                        data-index="${index}">
                                    -
                                </button>
                                <input type="number" 
                                    name="menu_items[${index}][quantity]" 
                                    value="${quantity}" 
                                    min="1" 
                                    class="w-16 px-2 py-2 text-center border-0 quantity-input"
                                    data-index="${index}"
                                    data-price="${menuPrice}">
                                <button type="button" 
                                        class="px-3 py-2 bg-gray-200 hover:bg-gray-300 increase-quantity transition-colors"
                                        data-index="${index}">
                                    +
                                </button>
                            </div>
                            
                            <button type="button" 
                                    class="remove-menu-item px-3 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors"
                                    data-index="${index}">
                                Remove
                            </button>
                            
                            <p class="font-medium w-24 text-right item-total" data-index="${index}">
                                Rp ${(menuPrice * quantity).toLocaleString('id-ID')}
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            menuItemsContainer.insertAdjacentHTML('beforeend', menuItemHtml);
            
            calculateReservationFee();
            showNotification('Menu item added successfully!', 'success');
        }

        // Initialize existing menu items
        initializeMenuItems();
        updateEmptyState();
    });

    function calculateReservationFee() {
        let menuTotal = 0;
        document.querySelectorAll('.menu-item').forEach(item => {
            const quantityInput = item.querySelector('.quantity-input');
            if (quantityInput) {
                const price = parseFloat(quantityInput.getAttribute('data-price'));
                const quantity = parseInt(quantityInput.value) || 0;
                menuTotal += price * quantity;
            }
        });
        
        // Update display
        document.getElementById('menu-total').textContent = 'Rp ' + menuTotal.toLocaleString('id-ID');
        document.getElementById('total-amount').textContent = 'Rp ' + menuTotal.toLocaleString('id-ID');
        document.getElementById('down-payment').textContent = 'Rp ' + (menuTotal * 0.1).toLocaleString('id-ID');
    }

        function addMenuItemToForm(menuId, menuName, menuPrice, menuImage, quantity = 1) {
            const menuItemsContainer = document.getElementById('menu-items-container');
            const emptyState = menuItemsContainer.querySelector('.text-center');
            
            if (emptyState) {
                emptyState.remove();
            }
            
            const existingItem = document.querySelector(`input[value="${menuId}"][name*="menu_id"]`);
            if (existingItem) {
                const quantityInput = existingItem.closest('.menu-item').querySelector('.quantity-input');
                const currentQuantity = parseInt(quantityInput.value);
                quantityInput.value = currentQuantity + quantity;
                calculateReservationFee();
                showNotification('Menu item quantity updated!', 'success');
                return;
            }
            
            const index = document.querySelectorAll('.menu-item').length;
            const menuItemHtml = `
                <div class="menu-item border rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center flex-1">
                            ${menuImage ? `<img src="${menuImage}" alt="${menuName}" class="w-16 h-16 object-cover rounded mr-4" onerror="this.style.display='none'">` : ''}
                            <div class="flex-1">
                                <h4 class="font-semibold">${menuName}</h4>
                                <p class="text-sm text-gray-600">Rp ${parseInt(menuPrice).toLocaleString('id-ID')} per item</p>
                                <input type="hidden" name="menu_items[${index}][menu_id]" value="${menuId}">
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <div class="flex items-center border rounded-lg overflow-hidden">
                                <input type="number" 
                                    name="menu_items[${index}][quantity]" 
                                    value="${quantity}" 
                                    min="1" 
                                    class="w-20 px-3 py-2 text-center border-0 quantity-input"
                                    data-price="${menuPrice}">
                            </div>
                            
                            <button type="button" 
                                    class="remove-menu-item px-3 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors">
                                Remove
                            </button>
                            
                            <p class="font-medium w-24 text-right item-total">
                                Rp ${(menuPrice * quantity).toLocaleString('id-ID')}
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            menuItemsContainer.insertAdjacentHTML('beforeend', menuItemHtml);
            
            // Initialize event listeners for new item
            const newItem = menuItemsContainer.lastElementChild;
            const quantityInput = newItem.querySelector('.quantity-input');
            const removeButton = newItem.querySelector('.remove-menu-item');
            
            quantityInput.addEventListener('input', calculateReservationFee);
            removeButton.addEventListener('click', function() {
                newItem.remove();
                calculateReservationFee();
                updateEmptyState();
            });
            
            calculateReservationFee();
            showNotification('Menu item added successfully!', 'success');
        }

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
            }, 3000);
        }


        function animateNotificationOut(notification) {
            notification.style.transition = 'all 0.3s ease-out';
            notification.style.opacity = '0';
            notification.style.transform = 'translate(-50%, -20px)';
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