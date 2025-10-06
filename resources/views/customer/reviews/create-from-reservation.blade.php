<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Menu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Review: {{ $menu->name }}</h3>
                    <p class="text-gray-600 mb-6">Review for {{ $menu->name }} from Reservation #{{ $reservation->id }}</p>

                    <form action="{{ route('customer.reviews.store-from-reservation', ['reservation' => $reservation, 'menu' => $menu]) }}" method="POST">
                        @csrf

                        <!-- Rating Stars -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex space-x-1" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" 
                                            class="text-2xl text-gray-300 hover:text-yellow-400 focus:outline-none star"
                                            data-rating="{{ $i }}"
                                            aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-input" value="0" required>
                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-6">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                Comment (Optional)
                            </label>
                            <textarea name="comment" id="comment" rows="4" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                                placeholder="Share your experience with this menu...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('customer.reservations.show', $reservation) }}" 
                               class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const ratingInput = document.getElementById('rating-input');
            
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    ratingInput.value = rating;
                    
                    // Update star display
                    stars.forEach((s, index) => {
                        if (index < rating) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
                
                star.addEventListener('mouseover', function() {
                    const hoverRating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach((s, index) => {
                        if (index < hoverRating) {
                            s.classList.add('text-yellow-300');
                        }
                    });
                });
                
                star.addEventListener('mouseout', function() {
                    const currentRating = parseInt(ratingInput.value);
                    stars.forEach((s, index) => {
                        s.classList.remove('text-yellow-300');
                        if (index < currentRating) {
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>