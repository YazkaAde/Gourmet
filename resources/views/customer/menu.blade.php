<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Our Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                    onerror="this.style.display='none'"
                                />
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Add to Cart Button -->
                    <button class="mt-4 w-full bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-lg transition">
                        Add to Cart
                    </button>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $menus->links() }}
            </div>

            <!-- Cart Button -->
            <a href="{{ route('customer.cart.index') }}" 
                class="fixed bottom-8 right-8 bg-primary-600 text-white flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm shadow-lg hover:bg-primary-700 transition">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M4 4a1 1 0 0 1 1-1h1.5a1 1 0 0 1 .979.796L7.939 6H19a1 1 0 0 1 .979 1.204l-1.25 6a1 1 0 0 1-.979.796H9.604l-.208 1H17a3 3 0 1 1-2.83 2h-2.34a3 3 0 1 1-4.009-1.76L5.686 5H5a1 1 0 0 1-1-1Z"/>
                </svg>
                Cart (0)
            </a>
        </div>
    </div>
</x-app-layout>