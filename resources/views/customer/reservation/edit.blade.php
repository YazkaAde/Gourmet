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
                            <a href="{{ route('customer.reservations.index', $reservation) }}" 
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableSelect = document.getElementById('table_number');
            const reservationFee = document.getElementById('reservation-fee');
            const downPayment = document.getElementById('down-payment');
            const timeInput = document.getElementById('reservation_time');

            timeInput.min = '09:00';
            timeInput.max = '21:00';

            timeInput.addEventListener('change', function() {
                const selectedTime = this.value;
                if (selectedTime) {
                    const [hours, minutes] = selectedTime.split(':').map(Number);
                    
                    if (hours < 9 || hours > 21) {
                        alert('Waktu reservasi hanya tersedia dari jam 09:00 sampai 21:00');
                        this.value = '{{ $reservation->reservation_time }}';
                    }
                }
            });

            function calculateReservationFee() {
                const selectedOption = tableSelect.options[tableSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const capacity = parseInt(selectedOption.getAttribute('data-capacity'));
                    let price = capacity * 10000;
                    
                    if (capacity >= 8) {
                        price = price * 0.8;
                    }
                    
                    const dp = price * 0.1;
                    
                    reservationFee.textContent = 'Rp ' + price.toLocaleString();
                    downPayment.textContent = 'Rp ' + dp.toLocaleString();
                } else {
                    reservationFee.textContent = 'Rp 0';
                    downPayment.textContent = 'Rp 0';
                }
            }

            tableSelect.addEventListener('change', calculateReservationFee);

            calculateReservationFee();
        });
    </script>
</x-app-layout>