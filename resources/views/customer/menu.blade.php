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
                <article class="bg-white flex flex-col rounded-lg border border-gray-200 p-5 shadow-sm h-full relative
                    @if($menu->status == 'available') 
                        hover:bg-gray-50 hover:shadow-md transition-all duration-200 
                    @else 
                        bg-gray-50 border-gray-300
                    @endif">
                    
                    <!-- Overlay untuk unavailable items -->
                    @if($menu->status == 'unavailable')
                        <div class="absolute inset-0 bg-white bg-opacity-80 rounded-lg z-10 backdrop-blur-[1px] flex items-center justify-center">
                            <div class="text-center p-4 bg-white rounded-lg shadow-sm border border-gray-200 mx-4">
                                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-red-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <p class="text-red-600 font-semibold text-sm mb-1">Currently Unavailable</p>
                                <p class="text-gray-500 text-xs">We'll be back soon!</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex-grow relative">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-grow">
                                <div class="flex items-start gap-2 mb-2">
                                    <h3 class="font-bold text-lg 
                                        @if($menu->status == 'unavailable') text-gray-500 @else text-gray-900 @endif">
                                        {{ $menu->name }}
                                    </h3>
                                    @if($menu->status == 'unavailable')
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full border border-red-200 font-medium shrink-0 mt-0.5">
                                            Unavailable
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm mb-2 
                                    @if($menu->status == 'unavailable') text-gray-400 @else text-gray-600 @endif">
                                    {{ $menu->category->name }}
                                </p>
                                <p class="text-sm mb-4 
                                    @if($menu->status == 'unavailable') text-gray-400 @else text-gray-500 @endif">
                                    {{ Str::limit($menu->description, 60) }}
                                </p>
                                <p class="font-semibold text-lg 
                                    @if($menu->status == 'unavailable') text-gray-500 @else text-gray-900 @endif">
                                    Rp {{ number_format($menu->price, 0) }}
                                </p>
                            </div>
                            @if($menu->image_url)
                            <div class="ml-4 flex-shrink-0">
                                <img 
                                    src="{{ asset('storage/' . $menu->image_url) }}" 
                                    alt="{{ $menu->name }}"
                                    class="w-20 h-20 object-cover rounded-lg 
                                        @if($menu->status == 'unavailable') opacity-60 @else border border-gray-200 @endif"
                                    onerror="this.style.display='none'"/>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Rating Section -->
                    @if($menu->rating_count > 0)
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400 
                            @if($menu->status == 'unavailable') opacity-50 @endif">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($menu->average_rating))
                                    ★
                                @elseif($i - 0.5 <= $menu->average_rating)
                                    ⯨
                                @else
                                    ☆
                                @endif
                            @endfor
                        </div>
                        <span class="text-sm ml-2 
                            @if($menu->status == 'unavailable') text-gray-400 @else text-gray-600 @endif">
                            ({{ number_format($menu->average_rating, 1) }}) • {{ $menu->rating_count }} reviews
                        </span>
                    </div>
                    @else
                    <div class="flex items-center mb-4">
                        <div class="flex text-gray-300 
                            @if($menu->status == 'unavailable') opacity-50 @endif">
                            @for($i = 1; $i <= 5; $i++)
                                ☆
                            @endfor
                        </div>
                        <span class="text-sm ml-2 
                            @if($menu->status == 'unavailable') text-gray-400 @else text-gray-500 @endif">
                            No reviews yet
                        </span>
                    </div>
                    @endif
                    
                    <!-- Add to Cart Form -->
                    @if($menu->status == 'available')
                        <form action="{{ route('customer.cart.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                                    <button type="button"
                                            class="quantity-decrement px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                            aria-label="Kurangi satu">
                                        −
                                    </button>

                                    <input type="number"
                                           name="quantity"
                                           value="1"
                                           min="1"
                                           class="quantity-input w-16 text-center border-x border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 py-2 bg-white">

                                    <button type="button"
                                            class="quantity-increment px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-bold transition-colors"
                                            aria-label="Tambah satu">
                                        +
                                    </button>
                                </div>

                                <!-- Add to Cart Button -->
                                <button type="submit" 
                                        class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                    </svg>
                                    Add
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Unavailable State - Informative but non-interactive -->
                        <div class="mt-auto p-3 bg-gray-100 rounded-lg border border-gray-200">
                            <div class="text-center">
                                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600 text-sm font-medium mb-1">Temporarily Unavailable</p>
                                <p class="text-gray-500 text-xs">We're restocking this item</p>
                            </div>
                        </div>
                    @endif
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $menus->links() }}
            </div>

            <!-- Cart & Reservation Buttons -->
            <a href="{{ route('customer.cart.index') }}" 
                class="fixed right-0 top-1/2 transform -translate-y-1/2 floating-btn bg-primary-600 text-white flex items-center justify-end overflow-hidden transition-all duration-300 ease-in-out w-12 hover:w-44 rounded-l-lg shadow-lg group z-50"
                id="cart-btn">
                <div class="flex items-center">
                    <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 mr-2 font-semibold">
                        Cart (<span id="cart-count">{{ auth()->user()->carts()->whereNull('order_id')->count() }}</span>)
                    </span>
                    <div class="bg-primary-600 p-3 rounded-l-lg">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.604l-.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('customer.reservations.create') }}" 
                class="fixed right-0 top-1/2 transform -translate-y-1/2 mt-16 floating-btn bg-green-600 text-white flex items-center justify-end overflow-hidden transition-all duration-300 ease-in-out w-12 hover:w-44 rounded-l-lg shadow-lg group z-50"
                id="reservation-btn">
                <div class="flex items-center">
                    <span class="whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-200 mr-2 font-semibold">
                        Reservasi
                    </span>
                    <div class="bg-green-600 p-3 rounded-l-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <style>
        .floating-btn {
            position: fixed;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .floating-btn:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            width: 11rem !important;
        }

        #cart-btn {
            margin-top: 0 !important;
        }

        #reservation-btn {
            margin-top: 2rem !important;
        }

        .floating-btn {
            width: 3rem;
        }

        .floating-btn:hover {
            width: 9rem !important;
        }

        /* Style untuk card unavailable */
        article {
            position: relative;
        }
    </style>

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
                // Hanya proses quantity controls untuk menu yang available
                const article = e.target.closest('article');
                if (article && article.classList.contains('bg-gray-50')) {
                    return; // Skip untuk menu unavailable
                }

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

            const cartBtn = document.getElementById('cart-btn');
            const reservationBtn = document.getElementById('reservation-btn');

            function setInitialPositions() {
                cartBtn.style.top = '50%';
                cartBtn.style.transform = 'translateY(-50%)';
                
                reservationBtn.style.top = 'calc(50% + 4rem)';
                reservationBtn.style.transform = 'translateY(-50%)';
            }

            setInitialPositions();

            [cartBtn, reservationBtn].forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    this.style.width = '11rem';
                });

                btn.addEventListener('mouseleave', function() {
                    this.style.width = '3rem';
                });
            });

            window.addEventListener('resize', setInitialPositions);
        });
    </script>
</x-app-layout>