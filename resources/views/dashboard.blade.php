<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <h2>ini halaman customer</h2>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @foreach($menus as $menu)
                <article class="bg-white hover:bg-gray-50 flex flex-col rounded-lg border border-gray-300 p-5 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-1">{{ $menu->name }}</h3>
                            <p class="text-gray-500 text-sm mb-8">{{ Str::limit($menu->description, 50) }}</p>
                            <p class="font-semibold text-gray-900 text-base">{{ number_format($menu->price, 2) }}</p>
                        </div>
                        @if($menu->image_url)
                        <img 
                            src="{{ asset($menu->image_url) }}" 
                            alt="{{ $menu->name }}" 
                            style="width: 110px; height: 100px; object-fit: cover;" 
                            class="self-center"
                            onerror="this.style.display='none'"
                        />@else
                            <img src="{{ asset($menu->image_url) }}" 
                            onerror="console.error('Gagal memuat gambar: {{ $menu->image_url }}')">
                        @endif
                    </div>
                    
                    <div class="flex space-x-2 justify-end">
                        <a href="{{ route('menus.edit', $menu->slug) }}" 
                            class="mt-auto self-start border border-green-700 text-green-700 rounded-xl px-6 py-2 font-semibold hover:bg-green-100 transition"
                            type="button"
                        >Edit</a>

                        <form action="{{ route('menus.destroy', $menu->slug) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                class="mt-auto self-start border border-red-700 text-red-700 rounded-xl px-6 py-2 font-semibold hover:bg-red-100 transition"
                                type="submit"
                                onclick="return confirm('Are you sure you want to delete this menu item?')"
                            >Delete</button>
                        </form>
                    </div>
                </article>
                @endforeach
            </div>
            
            <!-- add menu button bottom right -->
            <a href="{{ route('menus.create') }}" 
                aria-label="Menu"
                class="fixed bottom-8 right-8 bg-green-600 text-white flex items-center gap-2 px-6 py-3 rounded-full font-semibold text-sm shadow-lg hover:bg-green-700 transition"
                type="button"
            >
                <svg class="w-6 h-6 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
                </svg>                                 
                Add menu
            </a>
        </div>
    </div> --}}
</x-app-layout>
