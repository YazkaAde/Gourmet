<div id="addMenuModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-hidden">
        <div class="flex flex-col h-full">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    @if(isset($reservation) && $reservation)
                    Add Menu Items to Reservation #{{ $reservation->id }}
                    @else
                    Add Menu Items to Reservation
                    @endif
                </h3>
                <button onclick="closeAddMenuModal()" class="text-gray-400 hover:text-gray-600">
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
                        <div class="p-4">
                            <h4 class="text-lg font-semibold mb-4">Available Menu Items</h4>
                            @if($menus->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[60vh] overflow-y-auto pr-2">
                                    @foreach($menus as $menu)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start gap-4">
                                            @if($menu->image_url)
                                            <img src="{{ asset('storage/' . $menu->image_url) }}" 
                                                alt="{{ $menu->name }}"
                                                class="w-20 h-20 object-cover rounded flex-shrink-0"
                                                onerror="this.style.display='none'">
                                            @endif
                                            <div class="flex-1">
                                                <h5 class="font-semibold text-gray-900">{{ $menu->name }}</h5>
                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($menu->description, 100) }}</p>
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4 pt-4 border-t">
                <button onclick="closeAddMenuModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors font-medium">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
    // closeAddMenuModal();
}

function closeAddMenuModal() {
    const modal = document.getElementById('addMenuModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

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
</style>