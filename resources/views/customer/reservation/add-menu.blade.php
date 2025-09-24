<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Menu to Reservation') }} #{{ $reservation->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Modal toggle button -->
            <div class="flex justify-center m-5">
                <button id="addMenuModalButton" data-modal-target="addMenuModal" data-modal-toggle="addMenuModal" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-lg">
                    + Add Menu Items
                </button>
            </div>

            <!-- Current Reservation Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Current Reservation Summary</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <p class="text-sm text-gray-600">Guest Count</p>
                            <p class="font-medium">{{ $reservation->guest_count }} guests</p>
                        </div>
                    </div>

                    <!-- Current Menu Items -->
                    @if($reservation->orderItems->count() > 0)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-md font-semibold mb-3">Current Menu Items</h4>
                        <div class="space-y-2">
                            @foreach($reservation->orderItems as $item)
                            <div class="flex justify-between items-center p-2 border rounded">
                                <div class="flex items-center">
                                    @if($item->menu->image_url)
                                        <img src="{{ asset('storage/' . $item->menu->image_url) }}" 
                                            alt="{{ $item->menu->name }}"
                                            class="w-10 h-10 object-cover rounded mr-3">
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $item->menu->name }}</p>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × Rp {{ number_format($item->price, 0) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Rp {{ number_format($item->total_price, 0) }}</span>
                                    <form action="{{ route('customer.reservations.menu.remove', [$reservation, $item]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-2 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
                                                onclick="return confirm('Remove this item?')">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-between font-bold border-t pt-2">
                            <span>Menu Total:</span>
                            <span>Rp {{ number_format($reservation->menu_total, 0) }}</span>
                        </div>
                    </div>
                    @else
                    <div class="mt-4 text-center py-8 text-gray-500">
                        <p>No menu items added yet. Click the button above to add menu items.</p>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('customer.reservations.show', $reservation) }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Back to Reservation Details
                        </a>
                        
                        @if($reservation->orderItems->count() > 0)
                        <form action="{{ route('customer.reservations.menu.clear', $reservation) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
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

    <!-- Add Menu Modal -->
    <div id="addMenuModal" tabindex="-1" aria-hidden="true" 
         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-6xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex justify-between items-center p-5 rounded-t border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Add Menu Items to Reservation #{{ $reservation->id }}
                    </h3>
                    <button type="button" 
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" 
                            data-modal-toggle="addMenuModal">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                
                <!-- Modal body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Menu List -->
                        <div class="lg:col-span-2">
                            <h4 class="text-lg font-semibold mb-4">Available Menu Items</h4>
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                                @foreach($menus as $menu)
                                <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center flex-1">
                                        @if($menu->image_url)
                                        <img src="{{ asset('storage/' . $menu->image_url) }}" 
                                            alt="{{ $menu->name }}"
                                            class="w-16 h-16 object-cover rounded mr-4">
                                        @endif
                                        <div class="flex-1">
                                            <h5 class="font-semibold text-gray-900">{{ $menu->name }}</h5>
                                            <p class="text-sm text-gray-600 mb-1">{{ Str::limit($menu->description, 80) }}</p>
                                            <p class="text-primary-600 font-bold">Rp {{ number_format($menu->price, 0) }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('customer.reservations.menu.store', $reservation) }}" method="POST" class="add-menu-form flex items-center ml-4">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <div class="flex items-center">
                                            <input type="number" name="quantity" value="1" min="1" 
                                                class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center">
                                            <button type="submit" 
                                                    class="ml-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors">
                                                Add to Order
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination -->
                            @if($menus->hasPages())
                            <div class="mt-6">
                                {{ $menus->links() }}
                            </div>
                            @endif
                        </div>
                        
                        <!-- Reservation Summary Sidebar -->
                        <div class="lg:col-span-1">
                            <div class="sticky top-6 bg-gray-50 rounded-lg p-4 border">
                                <h4 class="text-lg font-semibold mb-4">Order Summary</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Table:</span>
                                        <span class="font-medium">{{ $reservation->table_number }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Date:</span>
                                        <span class="font-medium">{{ $reservation->reservation_date->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Time:</span>
                                        <span class="font-medium">{{ $reservation->reservation_time }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Guests:</span>
                                        <span class="font-medium">{{ $reservation->guest_count }}</span>
                                    </div>
                                    
                                    <div class="border-t pt-3 mt-3">
                                        <h5 class="font-medium mb-2">Current Items ({{ $reservation->orderItems->count() }})</h5>
                                        @if($reservation->orderItems->count() > 0)
                                        <div class="space-y-2 mb-3 max-h-40 overflow-y-auto">
                                            @foreach($reservation->orderItems as $item)
                                            <div class="flex justify-between text-xs bg-white p-2 rounded border">
                                                <span class="truncate">{{ $item->menu->name }} (x{{ $item->quantity }})</span>
                                                <span>Rp {{ number_format($item->total_price, 0) }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                        @else
                                        <p class="text-sm text-gray-500 text-center py-2">No items added yet</p>
                                        @endif
                                        
                                        <div class="border-t pt-3 space-y-2">
                                            <div class="flex justify-between text-sm">
                                                <span>Reservation Fee:</span>
                                                <span>Rp {{ number_format($reservation->reservation_fee, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span>Menu Total:</span>
                                                <span>Rp {{ number_format($reservation->menu_total, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                                                <span>Total Amount:</span>
                                                <span>Rp {{ number_format($reservation->total_amount, 0) }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-green-600">
                                                <span>Down Payment (10%):</span>
                                                <span>Rp {{ number_format($reservation->down_payment_amount, 0) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalToggleButtons = document.querySelectorAll('[data-modal-toggle]');
        
        modalToggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-modal-target');
                const modal = document.getElementById(target);
                if (modal) {
                    modal.classList.toggle('hidden');
                    if (modal.classList.contains('hidden')) {
                        document.body.style.overflow = 'auto';
                    } else {
                        document.body.style.overflow = 'hidden';
                    }
                }
            });
        });

        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                modals.forEach(modal => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            }
        });

        document.querySelectorAll('.add-menu-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.textContent;
                
                submitButton.textContent = 'Adding...';
                submitButton.disabled = true;
                
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                        showNotification('Menu item added successfully!', 'success');
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        throw new Error(data.message || 'Failed to add menu item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error adding menu item. Please try again.', 'error');
                    
                    submitButton.textContent = originalText;
                    submitButton.disabled = false;
                });
            });
        });

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 
                type === 'error' ? 'bg-red-500' : 'bg-blue-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        const modalButton = document.getElementById('addMenuModalButton');
        if (modalButton) {
            modalButton.addEventListener('click', function() {
                setTimeout(() => {
                    const firstQuantityInput = document.querySelector('.add-menu-form input[type="number"]');
                    if (firstQuantityInput) {
                        firstQuantityInput.focus();
                    }
                }, 300);
            });
        }
    });

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

    <style>
    .bg-black.bg-opacity-50 {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    #addMenuModal {
        transition: opacity 0.3s ease-in-out;
    }
    
    .max-h-96::-webkit-scrollbar {
        width: 6px;
    }
    
    .max-h-96::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .max-h-96::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
    </style>
</x-app-layout>