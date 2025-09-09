<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Menu to Reservation') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Menu List -->
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-semibold mb-4">Available Menu</h3>
                            <div class="space-y-4">
                                @foreach($menus as $menu)
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center">
                                        @if($menu->image_url)
                                        <img src="{{ asset('storage/' . $menu->image_url) }}" 
                                            alt="{{ $menu->name }}"
                                            class="w-16 h-16 object-cover rounded mr-4">
                                        @endif
                                        <div>
                                            <h4 class="font-semibold">{{ $menu->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ Str::limit($menu->description, 50) }}</p>
                                            <p class="text-primary-600 font-bold">Rp {{ number_format($menu->price, 0) }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('customer.reservations.add-menu', $reservation) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <div class="flex items-center">
                                            <input type="number" name="quantity" value="1" min="1" 
                                                class="w-16 px-2 py-1 border rounded mr-2">
                                            <button type="submit" class="px-3 py-1 bg-primary-600 text-white rounded hover:bg-primary-700">
                                                Add
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $menus->links() }}
                            </div>
                        </div>
                        
                        <!-- Cart Summary -->
                        <div class="md:col-span-1">
                            <div class="sticky top-6">
                                <h3 class="text-lg font-semibold mb-4">Reservation Summary</h3>
                                
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <div class="mb-4">
                                        <h4 class="font-medium mb-2">Reservation Details</h4>
                                        <p class="text-sm">Table: {{ $reservation->table_number }}</p>
                                        <p class="text-sm">Date: {{ $reservation->reservation_date->format('M d, Y') }}</p>
                                        <p class="text-sm">Time: {{ $reservation->reservation_time }}</p>
                                        <p class="text-sm">Guests: {{ $reservation->guest_count }}</p>
                                    </div>
                                    
                                    <div class="border-t pt-4">
                                        <h4 class="font-medium mb-2">Current Order</h4>
                                        @if($reservation->preOrderItems->count() > 0)
                                        <div class="space-y-2 mb-4">
                                            @foreach($reservation->preOrderItems as $item)
                                            <div class="flex justify-between text-sm">
                                                <span>{{ $item->menu->name }} (x{{ $item->quantity }})</span>
                                                <span>Rp {{ number_format($item->menu->price * $item->quantity, 0) }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-sm text-gray-500">No items added yet</p>
                                        @endif
                                        
                                        <div class="border-t pt-2">
                                            <div class="flex justify-between font-medium">
                                                <span>Reservation Fee:</span>
                                                <span>Rp {{ number_format($reservation->reservation_fee, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between font-medium">
                                                <span>Menu Total:</span>
                                                <span>Rp {{ number_format($reservation->preOrderItems->sum(function($item) { return $item->menu->price * $item->quantity; }), 0) }}</span>
                                            </div>
                                            <div class="flex justify-between font-bold text-lg mt-2">
                                                <span>Total:</span>
                                                <span>Rp {{ number_format($reservation->total_amount, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('customer.reservations.show', $reservation) }}" 
                                       class="px-4 py-2 bg-gray-300 text-gray-700 rounded text-center hover:bg-gray-400">
                                        Back to Reservation
                                    </a>
                                    @if($reservation->preOrderItems->count() > 0)
                                    <form action="{{ route('customer.reservations.clear-menu', $reservation) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                                onclick="return confirm('Are you sure you want to clear all menu items?')">
                                            Clear All Items
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>