<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make Reservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('customer.reservations.store') }}" method="POST" id="reservation-form">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="reservation_date" class="block text-sm font-medium text-gray-700">Tanggal Reservasi</label>
                                <input type="date" name="reservation_date" id="reservation_date" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    min="{{ date('Y-m-d') }}" 
                                    value="{{ old('reservation_date') }}" required>
                                @error('reservation_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reservation_time" class="block text-sm font-medium text-gray-700">Waktu Mulai Reservasi</label>
                                <input type="time" name="reservation_time" id="reservation_time" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('reservation_time') }}" required>
                                @error('reservation_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Jam operasional: 09:00 - 21:00</p>
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Berakhir Reservasi</label>
                                <input type="time" name="end_time" id="end_time" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Minimal 1 jam dari waktu mulai</p>
                            </div>

                            <div>
                                <label for="guest_count" class="block text-sm font-medium text-gray-700">Jumlah Tamu</label>
                                <input type="number" name="guest_count" id="guest_count" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    min="1" max="100" 
                                    value="{{ old('guest_count') }}" required>
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
                                            {{ old('table_number') == $table->table_number ? 'selected' : '' }}>
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
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old('notes') }}</textarea>
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
                                    @php
                                        $oldMenuItems = old('menu_items', []);
                                    @endphp
                                    @foreach($oldMenuItems as $index => $menuItem)
                                        @php
                                            $menu = $menus->firstWhere('id', $menuItem['menu_id']);
                                        @endphp
                                        @if($menu)
                                        <div class="menu-item border rounded-lg p-4" data-index="{{ $index }}">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center flex-1">
                                                    @if($menu->image_url)
                                                        <img src="{{ asset('storage/' . $menu->image_url) }}" 
                                                            alt="{{ $menu->name }}"
                                                            class="w-16 h-16 object-cover rounded mr-4"
                                                            onerror="this.style.display='none'">
                                                    @endif
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold">{{ $menu->name }}</h4>
                                                        <p class="text-sm text-gray-600">Rp {{ number_format($menu->price, 0) }} per item</p>
                                                        <input type="hidden" name="menu_items[{{ $index }}][menu_id]" value="{{ $menu->id }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-4">
                                                    <div class="flex items-center border rounded-lg overflow-hidden">
                                                        <button type="button" 
                                                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 decrease-quantity transition-colors"
                                                                data-index="{{ $index }}">
                                                            -
                                                        </button>
                                                        <input type="number" 
                                                            name="menu_items[{{ $index }}][quantity]" 
                                                            value="{{ $menuItem['quantity'] }}" 
                                                            min="1" 
                                                            class="w-16 px-2 py-2 text-center border-0 quantity-input"
                                                            data-index="{{ $index }}"
                                                            data-price="{{ $menu->price }}">
                                                        <button type="button" 
                                                                class="px-3 py-2 bg-gray-200 hover:bg-gray-300 increase-quantity transition-colors"
                                                                data-index="{{ $index }}">
                                                            +
                                                        </button>
                                                    </div>
                                                    
                                                    <button type="button" 
                                                            class="remove-menu-item px-3 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors"
                                                            data-index="{{ $index }}">
                                                        Remove
                                                    </button>
                                                    
                                                    <p class="font-medium w-24 text-right item-total" data-index="{{ $index }}">
                                                        Rp {{ number_format($menu->price * $menuItem['quantity'], 0) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>

                                @if(count($oldMenuItems) == 0)
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-lg" id="empty-state">
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
                                <p class="text-sm text-gray-600">Biaya Meja:</p>
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
                            <a href="{{ route('customer.menu.index') }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Kembali
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                Ajukan Reservasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Include Add Menu Modal --}}
    @include('customer.reservation.partials.add-menu-modal', ['reservation' => null, 'menus' => $menus])

    <script>
            document.addEventListener('DOMContentLoaded', function() {
        const menuItemsContainer = document.getElementById('menu-items-container');
        let menuItemIndex = {{ count(old('menu_items', [])) }};
        const tableSelect = document.getElementById('table_number');
        const reservationDateInput = document.getElementById('reservation_date');
        const reservationTimeInput = document.getElementById('reservation_time');
        const endTimeInput = document.getElementById('end_time');

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
                        calculateTotal();
                    }
                }
                
                // Handle tombol kurang
                if (e.target.classList.contains('decrease-quantity')) {
                    const index = e.target.getAttribute('data-index');
                    const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
                    if (input && parseInt(input.value) > 1) {
                        input.value = parseInt(input.value) - 1;
                        updateItemTotal(index);
                        calculateTotal();
                    }
                }
                
                // Handle tombol remove
                if (e.target.classList.contains('remove-menu-item')) {
                    const index = e.target.getAttribute('data-index');
                    const item = document.querySelector(`.menu-item[data-index="${index}"]`);
                    if (item) {
                        item.remove();
                        calculateTotal();
                        updateEmptyState();
                    }
                }
            });

            // Event listener untuk input quantity manual
            menuItemsContainer.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    const index = e.target.getAttribute('data-index');
                    updateItemTotal(index);
                    calculateTotal();
                }
            });
        }

        // Update item total display
        function updateItemTotal(index) {
            const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
            if (!input) return;
            
            const price = parseFloat(input.getAttribute('data-price'));
            const quantity = parseInt(input.value) || 0;
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

        // Calculate total reservation cost
        function calculateTotal() {
            // Calculate table fee
            const selectedOption = tableSelect.options[tableSelect.selectedIndex];
            let tableFee = 0;
            
            if (selectedOption && selectedOption.value) {
                const capacity = parseInt(selectedOption.getAttribute('data-capacity'));
                tableFee = capacity * 10000;
                
                if (capacity >= 8) {
                    tableFee = tableFee * 0.8;
                }
            }
            
            // Calculate menu total
            let menuTotal = 0;
            document.querySelectorAll('.menu-item').forEach(item => {
                const quantityInput = item.querySelector('.quantity-input');
                if (quantityInput) {
                    const price = parseFloat(quantityInput.getAttribute('data-price'));
                    const quantity = parseInt(quantityInput.value) || 0;
                    menuTotal += price * quantity;
                }
            });
            
            const totalAmount = tableFee + menuTotal;
            const downPayment = totalAmount * 0.1;
            
            // Update display
            document.getElementById('reservation-fee').textContent = 'Rp ' + tableFee.toLocaleString('id-ID');
            document.getElementById('menu-total').textContent = 'Rp ' + menuTotal.toLocaleString('id-ID');
            document.getElementById('total-amount').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            document.getElementById('down-payment').textContent = 'Rp ' + downPayment.toLocaleString('id-ID');
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
                if (quantityInput) {
                    const currentQuantity = parseInt(quantityInput.value) || 0;
                    quantityInput.value = currentQuantity + quantity;
                    updateItemTotal(existingIndex);
                    calculateTotal();
                    showNotification('Menu item quantity updated!', 'success');
                }
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
            
            calculateTotal();
            showNotification('Menu item added successfully!', 'success');
        }

        // Set business hours constraints
        reservationTimeInput.min = '09:00';
        reservationTimeInput.max = '21:00';
        endTimeInput.min = '10:00';
        endTimeInput.max = '21:00';

        // Time validation
        function validateTimeRange() {
            const startTime = reservationTimeInput.value;
            const endTime = endTimeInput.value;
            
            if (startTime && endTime) {
                const start = new Date('1970-01-01T' + startTime + 'Z');
                const end = new Date('1970-01-01T' + endTime + 'Z');
                
                const diffInHours = (end - start) / (1000 * 60 * 60);
                
                if (diffInHours < 1) {
                    endTimeInput.setCustomValidity('Waktu berakhir harus minimal 1 jam dari waktu mulai');
                    return false;
                } else {
                    endTimeInput.setCustomValidity('');
                    return true;
                }
            }
            return false;
        }

        // Auto-set end time when start time changes
        function autoSetEndTime() {
            if (reservationTimeInput.value && !endTimeInput.value) {
                const startTime = new Date('1970-01-01T' + reservationTimeInput.value + 'Z');
                const endTime = new Date(startTime.getTime() + 60 * 60 * 1000); // 1 jam
                
                const endTimeString = endTime.toTimeString().substring(0, 5);
                if (endTimeString <= '21:00') {
                    endTimeInput.value = endTimeString;
                }
            }
            validateTimeRange();
        }

        reservationTimeInput.addEventListener('change', autoSetEndTime);
        endTimeInput.addEventListener('change', validateTimeRange);

        tableSelect.addEventListener('change', calculateTotal);
        
        // Initialize
        initializeMenuItems();
        updateEmptyState();
        calculateTotal();
        validateTimeRange();
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