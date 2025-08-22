<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Our Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Notification Area -->
            @if (session('success'))
                <div id="success-notification" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg transition-opacity duration-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Category Filter -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('customer.menu.index') }}" 
                       class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('customer.menu.index', ['category' => $category->id]) }}" 
                           class="px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Menu Items -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($menus as $menu)
                <article class="bg-white hover:bg-gray-50 flex flex-col rounded-lg border border-gray-300 p-5 shadow-sm hover:shadow-md transition-shadow h-full">
                    <div class="flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-grow">
                                <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $menu->name }}</h3>
                                <p class="text-gray-500 text-sm mb-2">{{ $menu->category->name }}</p>
                                <p class="text-gray-500 text-sm mb-4">{{ Str::limit($menu->description, 50) }}</p>
                                <p class="font-semibold text-gray-900 text-lg">Rp {{ number_format($menu->price, 0) }}</p>
                            </div>
                            @if($menu->image_url)
                            <div class="ml-4 flex-shrink-0">
                                <img 
                                    src="{{ asset('storage/' . $menu->image_url) }}" 
                                    alt="{{ $menu->name }}"
                                    class="w-24 h-24 object-cover rounded-lg"
                                    onerror="this.style.display='none'"/>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <form action="{{ route('customer.cart.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                <button type="button"
                                        class="quantity-decrement px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold disabled:opacity-40 disabled:cursor-not-allowed"
                                        aria-label="Kurangi satu">
                                    −
                                </button>

                                <input type="number"
                                       name="quantity"
                                       value="1"
                                       min="1"
                                       class="quantity-input w-16 text-center border-x border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 py-2">

                                <button type="button"
                                        class="quantity-increment px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold"
                                        aria-label="Tambah satu">
                                    +
                                </button>
                            </div>

                            <!-- Add to Cart -->
                            <button type="submit" 
                                    class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Add
                            </button>
                        </div>
                    </form>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $menus->links() }}
            </div>

            <!-- Floating Cart Button -->
            <a href="{{ route('customer.cart.index') }}" 
                class="fixed bottom-8 right-8 bg-primary-600 text-white flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm shadow-lg hover:bg-primary-700 transition transform hover:scale-105">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.604l-.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"/>
                </svg>
                Cart (<span id="cart-count">{{ auth()->user()->carts->sum('quantity') ?? 0 }}</span>)
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateQuantityControl(input) {
                const min = parseInt(input.getAttribute('min')) || 1;
                const currentValue = parseInt(input.value) || min;
                const decrementBtn = input.parentElement.querySelector('.quantity-decrement');
        
                if (isNaN(currentValue) || currentValue < min) {
                    input.value = min;
                }
        
                decrementBtn.disabled = parseInt(input.value) <= min;
            }
        
            document.addEventListener('click', function(e) {
                const incrementBtn = e.target.closest('.quantity-increment');
                const decrementBtn = e.target.closest('.quantity-decrement');
        
                if (incrementBtn) {
                    const input = incrementBtn.parentElement.querySelector('.quantity-input');
                    input.value = parseInt(input.value) + 1;
                    updateQuantityControl(input);
                }
        
                if (decrementBtn) {
                    const input = decrementBtn.parentElement.querySelector('.quantity-input');
                    const min = parseInt(input.getAttribute('min')) || 1;
                    const currentValue = parseInt(input.value) || min;
        
                    if (currentValue > min) {
                        input.value = currentValue - 1;
                        updateQuantityControl(input);
                    }
                }
            });
        
            document.addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    updateQuantityControl(e.target);
                }
            });
        
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('quantity-input')) {
                    updateQuantityControl(e.target);
                }
            });
        
            document.querySelectorAll('.quantity-input').forEach(function(input) {
                updateQuantityControl(input);
            });
        
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.style.opacity = '0';
                    setTimeout(() => successNotification.remove(), 300);
                }, 3000);
            }
        });
        </script>
        

</x-app-layout>