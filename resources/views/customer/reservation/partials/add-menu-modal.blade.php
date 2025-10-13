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
                                                    <h5 class="font-semibold text-gray-900">{{ $menu->name }}</h5>
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
                                                            ({{ number_format($menu->average_rating, 1) }}) • {{ $menu->rating_count }} reviews
                                                        </span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-center">
                                                        <p class="text-primary-600 font-bold">Rp {{ number_format($menu->price, 0) }}</p>
                                                        <button type="button" 
                                                                onclick="addMenuItem('{{ $menu->id }}', '{{ addslashes($menu->name) }}', '{{ $menu->price }}', '{{ $menu->image_url ? asset('storage/' . $menu->image_url) : '' }}')"
                                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition-colors text-sm">
                                                            Add to Order
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
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

            <div class="flex justify-end p-6 border-t">
                <button onclick="closeAddMenuModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Single Notification Element -->
<div id="addMenuNotification" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[60] hidden">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="notificationMessage" class="font-medium"></span>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg flex items-center gap-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
        <span class="text-gray-700">Loading menu items...</span>
    </div>
</div>

<script>
// Single notification system
function showAddMenuNotification(message, type = 'success') {
    const notification = document.getElementById('addMenuNotification');
    const messageElement = document.getElementById('notificationMessage');
    
    // Set message and styling based on type
    messageElement.textContent = message;
    
    // Reset classes and set based on type
    notification.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[60]';
    
    let bgColor, icon;
    switch(type) {
        case 'success':
            bgColor = 'bg-green-500';
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            break;
        case 'error':
            bgColor = 'bg-red-500';
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
            break;
        default:
            bgColor = 'bg-blue-500';
            icon = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
    }
    
    notification.innerHTML = `
        <div class="${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 animate-pulse">
            ${icon}
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    // Show notification with animation
    notification.classList.remove('hidden');
    
    // Auto hide after 2 seconds
    setTimeout(() => {
        hideAddMenuNotification();
    }, 2000);
}

function hideAddMenuNotification() {
    const notification = document.getElementById('addMenuNotification');
    notification.classList.add('hidden');
}

function addMenuItem(menuId, menuName, menuPrice, menuImage, quantity = 1) {
    if (window.parent && window.parent.addMenuItemToForm) {
        window.parent.addMenuItemToForm(menuId, menuName, menuPrice, menuImage, quantity);
    } else if (window.opener && window.opener.addMenuItemToForm) {
        window.opener.addMenuItemToForm(menuId, menuName, menuPrice, menuImage, quantity);
    } else {
        if (typeof addMenuItemToForm === 'function') {
            addMenuItemToForm(menuId, menuName, menuPrice, menuImage, quantity);
        }
    }
}

function closeAddMenuModal() {
    const modal = document.getElementById('addMenuModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Show loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

// Hide loading overlay
function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

// Update category filter buttons styling
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

// Update category info panel
function updateCategoryInfo(categoryId, categoryName, itemCount) {
    const categoryInfo = document.getElementById('category-info');
    const currentCategoryText = document.getElementById('current-category-text');
    
    if (categoryId !== '') {
        currentCategoryText.textContent = `Showing ${itemCount} items from ${categoryName} category`;
        categoryInfo.classList.remove('hidden');
    } else {
        categoryInfo.classList.add('hidden');
    }
}

// Clear category filter
function clearCategoryFilter() {
    loadMenuContent('');
}

// Load menu content via AJAX
function loadMenuContent(category = '') {
    showLoading();
    
    // Build URL with parameters
    let url = '{{ request()->url() }}?';
    const params = new URLSearchParams();
    
    if (category) {
        params.append('category', category);
    }
    
    // Remove pagination parameters
    params.append('per_page', 'all'); // Get all items
    
    url += params.toString();
    
    // Fetch the content
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('#menu-content');
        
        if (newContent) {
            document.getElementById('menu-content').innerHTML = newContent.innerHTML;
        }
        
        // Update URL without reload
        window.history.pushState({}, '', url);
        
        // Update category filter buttons
        updateCategoryFilterButtons(category);
        
        // Update category info
        const categoryName = category ? document.querySelector(`[data-category="${category}"]`)?.textContent || 'selected' : '';
        const itemCount = document.querySelectorAll('.menu-item').length;
        updateCategoryInfo(category, categoryName, itemCount);
        
        hideLoading();
    })
    .catch(error => {
        console.error('Error loading menu content:', error);
        document.getElementById('menu-content').innerHTML = `
            <div class="text-center py-8">
                <p class="text-red-500">Error loading menu items. Please try again.</p>
                <button onclick="loadMenuContent('${category}')" 
                        class="mt-2 px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                    Retry
                </button>
            </div>
        `;
        hideLoading();
    });
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
        
        const categoryName = document.querySelector(`[data-category="${currentCategory}"]`)?.textContent || 'selected';
        const itemCount = document.querySelectorAll('.menu-item').length;
        updateCategoryInfo(currentCategory, categoryName, itemCount);
    }
});

window.addEventListener('popstate', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category') || '';
    loadMenuContent(category);
});
</script>

<style>
    /* Custom styles untuk modal */
    .bg-black.bg-opacity-50 {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Smooth transitions */
    #addMenuModal {
        transition: opacity 0.3s ease-in-out;
    }

    /* Scrollbar styling */
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

    /* Category filter button transitions */
    .category-filter-btn {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .category-filter-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Menu item animations */
    .menu-item {
        transition: all 0.3s ease;
    }

    .menu-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Loading animation */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>