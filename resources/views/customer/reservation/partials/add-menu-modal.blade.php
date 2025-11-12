<div id="addMenuModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative border w-full max-w-7xl shadow-lg rounded-md bg-white max-h-[95vh] overflow-hidden">
            <!-- Modal header -->
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    @if(isset($reservation) && $reservation)
                    Add Menu Items to Reservation #{{ $reservation->id }}
                    @else
                    Add Menu Items to Reservation
                    @endif
                </h3>
                <button onclick="closeAddMenuModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Modal body -->
            <div class="flex-1 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 h-full">
                    <!-- Menu List -->
                    <div class="lg:col-span-3">
                        <div class="p-6">
                            <!-- Category Filter -->
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold mb-4">Filter by Category</h4>
                                <div class="flex flex-wrap gap-2" id="category-filters">
                                    <button type="button" 
                                            data-category=""
                                            class="category-filter-btn px-4 py-2 rounded-full {{ !request('category') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                                        All
                                    </button>
                                    @foreach($categories as $category)
                                        <button type="button" 
                                                data-category="{{ $category->id }}"
                                                class="category-filter-btn px-4 py-2 rounded-full {{ request('category') == $category->id ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-800' }}">
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <h4 class="text-lg font-semibold mb-4">Available Menu Items</h4>
                            
                            <div id="menu-content">
                                @if($menus->count() > 0)
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[50vh] overflow-y-auto pr-2" id="menu-items-grid">
                                        @foreach($menus as $menu)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow menu-item">
                                            <div class="flex items-start gap-4">
                                                @if($menu->image_url)
                                                <img src="{{ asset('storage/' . $menu->image_url) }}" 
                                                    alt="{{ $menu->name }}"
                                                    class="w-20 h-20 object-cover rounded flex-shrink-0"
                                                    onerror="this.style.display='none'">
                                                @endif
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <h5 class="font-semibold text-gray-900">{{ $menu->name }}</h5>
                                                        {{-- Status Badge --}}
                                                        <span class="px-2 py-1 text-xs rounded-full font-medium 
                                                            {{ $menu->status == 'available' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                            {{ ucfirst($menu->status) }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 mb-2">{{ $menu->category->name }}</p>
                                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($menu->description, 100) }}</p>
                                                    
                                                    <!-- Rating Display -->
                                                    <div class="flex items-center mb-2">
                                                        <div class="flex text-yellow-400 text-sm">
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
                                                        <span class="text-xs text-gray-600 ml-2">
                                                            ({{ number_format($menu->average_rating, 1) }}) - {{ $menu->rating_count }} reviews
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-center">
                                                        <p class="text-primary-600 font-bold">Rp {{ number_format($menu->price, 0) }}</p>
                                                        <button type="button" 
                                                            onclick="addMenuItemToReservation({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }})"
                                                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors text-sm
                                                            {{ $menu->status != 'available' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                            {{ $menu->status != 'available' ? 'disabled' : '' }}>
                                                            {{ $menu->status == 'available' ? 'Add to Order' : 'Unavailable' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <!-- Pagination -->
                                    @if($menus->hasPages())
                                    <div class="mt-6 border-t pt-4">
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm text-gray-700">
                                                Showing {{ $menus->firstItem() }} to {{ $menus->lastItem() }} of {{ $menus->total() }} results
                                            </div>
                                            <div class="flex space-x-2">
                                                {{-- Previous Page Link --}}
                                                @if ($menus->onFirstPage())
                                                    <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed text-sm">Previous</span>
                                                @else
                                                    <button onclick="loadMenuPage('{{ $menus->previousPageUrl() }}')" 
                                                            class="px-3 py-1 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm transition-colors">
                                                        Previous
                                                    </button>
                                                @endif

                                                {{-- Page Numbers --}}
                                                @foreach ($menus->getUrlRange(1, $menus->lastPage()) as $page => $url)
                                                    @if ($page == $menus->currentPage())
                                                        <span class="px-3 py-1 bg-primary-600 text-white rounded text-sm">{{ $page }}</span>
                                                    @else
                                                        <button onclick="loadMenuPage('{{ $url }}')" 
                                                                class="px-3 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm transition-colors">
                                                            {{ $page }}
                                                        </button>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($menus->hasMorePages())
                                                    <button onclick="loadMenuPage('{{ $menus->nextPageUrl() }}')" 
                                                            class="px-3 py-1 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm transition-colors">
                                                        Next
                                                    </button>
                                                @else
                                                    <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed text-sm">Next</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                    <div class="text-center py-8">
                                        <p class="text-gray-500">No menus available at the moment.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Instructions Sidebar -->
                    <div class="lg:col-span-1 border-l pl-6">
                        <div class="sticky top-0 py-4">
                            <h4 class="text-lg font-semibold mb-4">How to Add Items</h4>
                            <div class="space-y-3 text-sm text-gray-600">
                                <div class="flex items-start gap-2">
                                    <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">1</span>
                                    <span>Click "Add to Order" on any menu item</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">2</span>
                                    <span>Items will be added to your reservation form</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">3</span>
                                    <span>Adjust quantities directly in the form</span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <span class="bg-blue-100 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mt-0.5">4</span>
                                    <span>Close this modal when finished</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h5 class="font-semibold mb-2">Tips</h5>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• You can add multiple items</li>
                                    <li>• Quantities can be adjusted later</li>
                                    <li>• Items can be removed if needed</li>
                                    <li>• Prices include tax and service</li>
                                </ul>
                            </div>

                            <!-- Selected Category Info -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg hidden" id="category-info">
                                <h5 class="font-semibold mb-2 text-blue-800">Filter Active</h5>
                                <p class="text-xs text-blue-600" id="current-category-text"></p>
                                <button type="button" 
                                        onclick="clearCategoryFilter()"
                                        class="mt-2 text-xs text-blue-600 hover:text-blue-800 underline">
                                    Clear Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-between items-center p-6 border-t">
                <div class="text-sm text-gray-500" id="pagination-info">
                    @if($menus->count() > 0)
                        Page {{ $menus->currentPage() }} of {{ $menus->lastPage() }}
                    @endif
                </div>
                <button onclick="closeAddMenuModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Element -->
<div id="addMenuNotification" class="fixed top-4 right-4 z-[60] hidden">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="notificationMessage" class="font-medium"></span>
    </div>
</div>

<script>
// Main function to add menu item to reservation
async function addMenuItemToReservation(menuId, menuName, menuPrice, quantity = 1) {
    console.log('Adding menu item:', { menuId, menuName, menuPrice, quantity });
    
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = 'Adding...';
    button.disabled = true;
    
    try {
        // Prepare form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('menu_id', menuId);
        formData.append('quantity', quantity);
        
        // Determine the correct route based on context
        let storeRoute;
        
        @if(isset($reservation) && $reservation)
            // For existing reservation (edit/show pages)
            storeRoute = "{{ route('customer.reservations.menu.store', $reservation) }}";
            console.log('Using reservation route:', storeRoute);
        @else
            console.log('No reservation yet, adding to form directly');
            
            // Call global function to add to form (defined in create/edit blades)
            if (typeof window.addMenuItemToForm === 'function') {
                window.addMenuItemToForm(menuId, menuName, menuPrice, null, quantity);
                showAddMenuNotification('✓ Menu item added to reservation form!', 'success');
                
                // Restore button
                button.innerHTML = originalText;
                button.disabled = false;
                return;
            } else {
                throw new Error('Cannot add menu item - reservation not created yet');
            }
        @endif
        
        console.log('Sending request to:', storeRoute);
        
        // Send request only if we have a reservation
        @if(isset($reservation) && $reservation)
        const response = await fetch(storeRoute, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Server response:', data);
        
        if (data.success) {
            showAddMenuNotification('✓ ' + data.message, 'success');
            
            setTimeout(() => {
                closeAddMenuModal();
                window.location.reload();
            }, 1500);
            
        } else {
            throw new Error(data.message || 'Failed to add menu item');
        }
        @endif
        
    } catch (error) {
        console.error('Error:', error);
        showAddMenuNotification('✗ Error: ' + error.message, 'error');
        
        // Restore button even on error
        button.innerHTML = originalText;
        button.disabled = false;
    }
}

// Notification functions
function showAddMenuNotification(message, type = 'success') {
    const notification = document.getElementById('addMenuNotification');
    const messageElement = document.getElementById('notificationMessage');
    
    // Set background color based on type
    let bgColor;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-500';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            break;
        case 'info':
            bgColor = 'bg-blue-500';
            break;
        default:
            bgColor = 'bg-blue-500';
    }
    
    notification.className = `fixed top-4 right-4 z-[60] ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3`;
    messageElement.textContent = message;
    
    notification.classList.remove('hidden');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideAddMenuNotification();
    }, 3000);
}

function hideAddMenuNotification() {
    const notification = document.getElementById('addMenuNotification');
    notification.classList.add('hidden');
}

// Modal control functions
function closeAddMenuModal() {
    const modal = document.getElementById('addMenuModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Filter functions
function updateCategoryFilterButtons(selectedCategory = '') {
    const filterButtons = document.querySelectorAll('.category-filter-btn');
    filterButtons.forEach(btn => {
        const category = btn.getAttribute('data-category');
        if (category === selectedCategory) {
            btn.classList.remove('bg-gray-200', 'text-gray-800');
            btn.classList.add('bg-primary-600', 'text-white');
        } else {
            btn.classList.remove('bg-primary-600', 'text-white');
            btn.classList.add('bg-gray-200', 'text-gray-800');
        }
    });
}

function updateCategoryInfo(categoryName, itemCount) {
    const categoryInfo = document.getElementById('category-info');
    const currentCategoryText = document.getElementById('current-category-text');
    
    if (categoryName && categoryName !== '') {
        currentCategoryText.textContent = `Showing ${itemCount} items from ${categoryName} category`;
        categoryInfo.classList.remove('hidden');
    } else {
        categoryInfo.classList.add('hidden');
    }
}

function clearCategoryFilter() {
    // Reload without category filter
    const url = new URL(window.location);
    url.searchParams.delete('category');
    window.history.pushState({}, '', url);
    loadMenuContent();
}

// AJAX loading functions
async function loadMenuContent(category = '') {
    try {
        let url = '{{ request()->url() }}';
        const params = new URLSearchParams();
        
        if (category) {
            params.append('category', category);
        }
        
        // Add existing pagination parameters
        const currentUrl = new URL(window.location);
        const page = currentUrl.searchParams.get('page');
        if (page) {
            params.append('page', page);
        }
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('#menu-content');
        
        if (newContent) {
            document.getElementById('menu-content').innerHTML = newContent.innerHTML;
        }
        
        // Update URL without page reload
        window.history.pushState({}, '', url);
        
        // Update UI states
        updateCategoryFilterButtons(category);
        
        const itemCount = document.querySelectorAll('.menu-item').length;
        const categoryName = category || '';
        updateCategoryInfo(categoryName, itemCount);
        
    } catch (error) {
        console.error('Error loading menu content:', error);
        showAddMenuNotification('Error loading menu items', 'error');
    }
}

async function loadMenuPage(url) {
    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('#menu-content');
        
        if (newContent) {
            document.getElementById('menu-content').innerHTML = newContent.innerHTML;
        }
        
        window.history.pushState({}, '', url);
        
    } catch (error) {
        console.error('Error loading menu page:', error);
        showAddMenuNotification('Error loading page', 'error');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        if (e.target.closest('.category-filter-btn')) {
            e.preventDefault();
            const button = e.target.closest('.category-filter-btn');
            const category = button.getAttribute('data-category');
            loadMenuContent(category);
        }
    });
    
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addMenuModal');
        if (e.target === modal) {
            closeAddMenuModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddMenuModal();
        }
    });
    
    const urlParams = new URLSearchParams(window.location.search);
    const currentCategory = urlParams.get('category') || '';
    if (currentCategory) {
        updateCategoryFilterButtons(currentCategory);
        
        const itemCount = document.querySelectorAll('.menu-item').length;
        updateCategoryInfo(currentCategory, itemCount);
    }
});

window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category') || '';
    const page = urlParams.get('page') || '1';
    
    loadMenuContent(category);
});
</script>