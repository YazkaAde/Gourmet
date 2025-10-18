<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Review Details') }}
                </h2>
                <div class="flex items-center mt-1">
                    <div class="flex text-yellow-400 mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($averageRating))
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600">{{ number_format($averageRating, 1) }} ({{ $reviews->total() }} reviews)</span>
                </div>
            </div>
            <!-- Perbaikan: Menggunakan flex-wrap dan mengurangi spacing -->
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.reviews.index') }}" class="bg-gray-500 text-white px-3 py-2 rounded-lg hover:bg-gray-600 text-sm transition duration-200 whitespace-nowrap">
                    ← Back to Reviews
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-primary-600 text-white px-3 py-2 rounded-lg hover:bg-primary-700 text-sm transition duration-200 whitespace-nowrap">
                    Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Tambah margin top untuk memberi jarak dari header -->
    <div class="py-8 mt-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Menu Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        @if($menu->image_url)
                            <img class="h-24 w-24 rounded-lg object-cover flex-shrink-0" src="{{ asset('storage/' . $menu->image_url) }}" alt="{{ $menu->name }}">
                        @else
                            <div class="h-24 w-24 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 border border-gray-200">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">{{ $menu->name }}</h3>
                            <p class="text-gray-600 mt-2 leading-relaxed">{{ $menu->description }}</p>
                            <div class="mt-3 flex flex-wrap items-center gap-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    Rp {{ number_format($menu->price, 0, ',', '.') }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    {{ $menu->category->name ?? 'Uncategorized' }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ $reviews->total() }} Reviews
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Customer Reviews</h3>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            {{ $reviews->total() }} total
                        </span>
                    </div>
                    
                    @forelse($reviews as $review)
                        <div class="border border-gray-100 rounded-lg p-6 mb-4 last:mb-0 bg-white hover:bg-gray-50 transition duration-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $review->rating }}/5</span>
                                </div>
                                <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-gray-700 leading-relaxed {{ !$review->comment ? 'text-gray-400 italic' : '' }}">
                                    {{ $review->comment ?? 'No comment provided' }}
                                </p>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-600 mb-4 space-x-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>{{ $review->user->name ?? 'Unknown User' }}</span>
                                </div>
                                @if($review->order_id)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Order #{{ $review->order_id }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Admin Reply Section -->
                            @if($review->admin_reply)
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mt-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                            </svg>
                                            <span class="text-sm font-medium text-blue-900">Admin Reply</span>
                                        </div>
                                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded">
                                            @if($review->replied_at)
                                                {{ $review->replied_at->format('M d, Y H:i') }}
                                            @else
                                                {{ $review->updated_at->format('M d, Y H:i') }}
                                            @endif
                                        </span>
                                    </div>
                                    <p class="text-blue-800 mb-3">{{ $review->admin_reply }}</p>
                                    <form action="{{ route('admin.reviews.reply', $review) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="flex space-x-2">
                                            <input type="text" name="admin_reply" value="{{ $review->admin_reply }}" 
                                                   class="flex-1 border border-blue-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                   placeholder="Update your reply...">
                                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition duration-200">
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('admin.reviews.reply', $review) }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('POST')
                                    <div class="flex space-x-2">
                                        <input type="text" name="admin_reply" 
                                               class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500" 
                                               placeholder="Write a reply to this review..." required>
                                        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 text-sm font-medium transition duration-200">
                                            Reply
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Your reply will be visible to the customer.</p>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
                            <p class="text-gray-500 max-w-md mx-auto">This menu hasn't received any reviews yet. Reviews will appear here once customers start rating this item.</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>