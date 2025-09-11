<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter Status -->
                    <div class="mb-6 flex gap-2 flex-wrap">
                        <a href="{{ route('cashier.reservations.index') }}" 
                           class="px-4 py-2 rounded {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            All Reservations
                        </a>
                        <a href="{{ route('cashier.reservations.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Pending
                        </a>
                        <a href="{{ route('cashier.reservations.index', ['status' => 'confirmed']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'confirmed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Confirmed
                        </a>
                        <a href="{{ route('cashier.reservations.index', ['status' => 'cancelled']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'cancelled' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Cancelled
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reservations as $reservation)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#{{ $reservation->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $reservation->reservation_date->format('M d, Y') }}<br>
                                        {{ $reservation->reservation_time }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Table {{ $reservation->table_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $reservation->guest_count }} guests</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            @if($reservation->status == 'confirmed') bg-green-100 text-green-800
                                            @elseif($reservation->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($reservation->status == 'cancelled') bg-red-100 text-red-800 
                                            @endif">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('cashier.reservations.show', $reservation) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                                        
                                           @if($reservation->status == 'pending')
                                           <span class="text-yellow-600">Menunggu DP</span>
                                           @elseif($reservation->status == 'confirmed')
                                           <form action="{{ route('cashier.reservations.update-status', $reservation) }}" method="POST" class="inline">
                                               @csrf
                                               @method('PATCH')
                                               <input type="hidden" name="status" value="cancelled">
                                               <button type="submit" 
                                                       class="text-red-600 hover:text-red-800"
                                                       onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                                   Cancel
                                               </button>
                                           </form>
                                           @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reservations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>