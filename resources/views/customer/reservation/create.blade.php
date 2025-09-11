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
                    <form action="{{ route('customer.reservations.store') }}" method="POST">
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
                                <label for="reservation_time" class="block text-sm font-medium text-gray-700">Waktu Reservasi</label>
                                <input type="time" name="reservation_time" id="reservation_time" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                    value="{{ old('reservation_time') }}" required>
                                @error('reservation_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                            data-price="{{ $table->table_capacity * 10000 }}"
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
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Menu (Opsional)</h3>
                                <div id="menu-items-container">
                                    @php
                                        $oldMenuItems = old('menu_items', []);
                                    @endphp
                                    @foreach($oldMenuItems as $index => $menuItem)
                                        <div class="menu-item border rounded-lg p-4 mb-4">
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Pilih Menu</label>
                                                    <select name="menu_items[{{ $index }}][menu_id]" 
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 menu-select">
                                                        <option value="">Pilih Menu</option>
                                                        @foreach($menus as $menu)
                                                            <option value="{{ $menu->id }}" data-price="{{ $menu->price }}"
                                                                {{ $menuItem['menu_id'] == $menu->id ? 'selected' : '' }}>
                                                                {{ $menu->name }} - Rp {{ number_format($menu->price, 0) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                                    <input type="number" name="menu_items[{{ $index }}][quantity]" 
                                                        value="{{ $menuItem['quantity'] }}" min="1" 
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 quantity-input">
                                                </div>
                                                <div class="flex items-end">
                                                    <button type="button" class="remove-menu-item px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-600">Subtotal: <span class="menu-subtotal">Rp 0</span></p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                <div class="mt-4">
                                    <button type="button" id="add-menu-item" 
                                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                        + Tambah Menu
                                    </button>
                                </div>
                            </div>

                            <template id="menu-item-template">
                                <div class="menu-item border rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Pilih Menu</label>
                                            <select name="menu_items[INDEX][menu_id]" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 menu-select">
                                                <option value="">Pilih Menu</option>
                                                @foreach($menus as $menu)
                                                    <option value="{{ $menu->id }}" data-price="{{ $menu->price }}">
                                                        {{ $menu->name }} - Rp {{ number_format($menu->price, 0) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                            <input type="number" name="menu_items[INDEX][quantity]" value="1" min="1" 
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500 quantity-input">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-menu-item px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600">Subtotal: <span class="menu-subtotal">Rp 0</span></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Reservation Summary -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-md">
                            <h3 class="text-lg font-medium text-gray-900">Ringkasan Reservasi</h3>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                <p class="text-sm text-gray-600">Biaya Reservasi:</p>
                                <p class="text-sm font-medium" id="reservation-fee">Rp 0</p>
                                
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItemsContainer = document.getElementById('menu-items-container');
            const addMenuItemButton = document.getElementById('add-menu-item');
            const menuItemTemplate = document.getElementById('menu-item-template');
            const reservationDateInput = document.getElementById('reservation_date');
            const reservationTimeInput = document.getElementById('reservation_time');
            const tableSelect = document.getElementById('table_number');
            let menuItemCount = {{ count(old('menu_items', [])) }};

                async function checkTableAvailability() {
            const date = reservationDateInput.value;
            const time = reservationTimeInput.value;
            
            if (!date || !time) return;
            
            try {
                const response = await fetch(`/api/available-tables?date=${date}&time=${time}`);
                const availableTables = await response.json();
                
                Array.from(tableSelect.options).forEach(option => {
                    if (option.value) {
                        const isAvailable = availableTables.some(table => table.table_number == option.value);
                        option.disabled = !isAvailable;
                        if (!isAvailable && option.selected) {
                            option.selected = false;
                            tableSelect.value = '';
                        }
                    }
                });
            } catch (error) {
                console.error('Error checking table availability:', error);
            }
        }
        
        reservationDateInput.addEventListener('change', checkTableAvailability);
        reservationTimeInput.addEventListener('change', checkTableAvailability);
        
        if (reservationDateInput.value && reservationTimeInput.value) {
            checkTableAvailability();
        }
        
            addMenuItemButton.addEventListener('click', function() {
                const templateContent = menuItemTemplate.innerHTML;
                const newMenuItem = templateContent.replace(/INDEX/g, menuItemCount);
                
                const div = document.createElement('div');
                div.innerHTML = newMenuItem;
                menuItemsContainer.appendChild(div);
                
                const select = div.querySelector('.menu-select');
                const quantityInput = div.querySelector('.quantity-input');
                const removeButton = div.querySelector('.remove-menu-item');
                const subtotalElement = div.querySelector('.menu-subtotal');
                
                const calculateSubtotal = function() {
                    if (select.value && quantityInput.value) {
                        const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price'));
                        const quantity = parseInt(quantityInput.value);
                        const subtotal = price * quantity;
                        subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString();
                    } else {
                        subtotalElement.textContent = 'Rp 0';
                    }
                    calculateTotal();
                };
                
                select.addEventListener('change', calculateSubtotal);
                quantityInput.addEventListener('input', calculateSubtotal);
                
                removeButton.addEventListener('click', function() {
                    div.remove();
                    calculateTotal();
                });
                
                menuItemCount++;
            });
        
            function calculateTotal() {
                let total = parseFloat(document.getElementById('reservation-fee').textContent.replace(/[^\d]/g, '') || 0);
                
                document.querySelectorAll('.menu-item').forEach(item => {
                    const subtotalText = item.querySelector('.menu-subtotal').textContent;
                    const subtotal = parseFloat(subtotalText.replace(/[^\d]/g, '') || 0);
                    total += subtotal;
                });
                
                const dp = total * 0.1;
                document.getElementById('down-payment').textContent = 'Rp ' + dp.toLocaleString();
            }

            calculateTotal();
            
            document.querySelectorAll('.menu-item').forEach(item => {
                const select = item.querySelector('.menu-select');
                const quantityInput = item.querySelector('.quantity-input');
                const subtotalElement = item.querySelector('.menu-subtotal');
                
                if (select.value && quantityInput.value) {
                    const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price'));
                    const quantity = parseInt(quantityInput.value);
                    const subtotal = price * quantity;
                    subtotalElement.textContent = 'Rp ' + subtotal.toLocaleString();
                }
            });
        });
    </script>
</x-app-layout>