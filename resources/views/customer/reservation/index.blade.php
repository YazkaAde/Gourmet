<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reservations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($reservations->isEmpty())
                <div class="bg-white p-6 rounded-lg shadow text-center">
                    <p class="text-gray-600 mb-4">You don't have any reservations yet</p>
                    <a href="{{ route('customer.reservations.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-white hover:bg-primary-700 transition">
                        Make Reservation
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="space-y-6">
                            @foreach($reservations as $reservation)
                                <div class="p-4 border rounded-lg bg-white hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">Reservation #{{ $reservation->id }}</h3>
                                            <p class="text-gray-600">Table: {{ $reservation->table_number }} ({{ $reservation->table->table_capacity }} people)</p>
                                            <p class="text-gray-600">Guests: {{ $reservation->guest_count }}</p>
                                            <p class="text-gray-600">Date: {{ $reservation->reservation_date->format('M d, Y') }} at {{ $reservation->reservation_time }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                                @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                                @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                            <p class="text-lg font-bold text-gray-900 mt-2">
                                                Fee: Rp {{ number_format($reservation->reservation_fee, 0) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-4 pt-4 border-t flex gap-3 items-center">
                                        <a href="{{ route('customer.reservations.show', $reservation) }}" 
                                           class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition">
                                            View Details
                                        </a>

                                        @if($reservation->status == 'pending' && !$reservation->payments()->where('status', 'paid')->exists())
                                            <a href="{{ route('customer.reservations.edit', $reservation) }}" 
                                               class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                                Edit
                                            </a>
                                            
                                            @if($reservation->status == 'pending' && !$reservation->payments()->where('status', 'paid')->exists())
                                                <a href="{{ route('customer.reservations.payment.create', $reservation) }}" 
                                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                                    Pay Now
                                                </a>
                                            @elseif($reservation->status == 'pending' && $reservation->payments()->where('status', 'paid')->exists() && $reservation->total_amount > $reservation->payments()->where('status', 'paid')->sum('amount'))
                                                <a href="{{ route('customer.reservations.payment.create', $reservation) }}" 
                                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                                    Pay Remaining
                                                </a>
                                            @endif
                                            
                                            <form action="{{ route('customer.reservations.cancel', $reservation) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                                                        onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                        @if($reservation->payments()->exists())
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-600">
                                                Paid: Rp {{ number_format($reservation->total_paid, 0) }} / 
                                                Rp {{ number_format($reservation->reservation_fee, 0) }}
                                            </p>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-primary-600 h-2 rounded-full" 
                                                    style="width: {{ ($reservation->total_paid / $reservation->reservation_fee) * 100 }}%"></div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reservations->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>