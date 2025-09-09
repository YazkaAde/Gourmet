@php
    use Illuminate\Support\Facades\Auth;
    $role = Auth::check() ? Auth::user()->role : null;
@endphp

<!-- Mobile sidebar toggle button -->
<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" 
        class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
    </svg>
</button>

<!-- Sidebar -->
<aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-8">
            @if($role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            @elseif($role === 'cashier')
                <a href="{{ route('cashier.dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                </a>
            @endif
        </div>

        <ul class="space-y-2">
            @if($role === 'admin')
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                
                <!-- Menu Management -->
                <li>
                    <a href="{{ route('admin.menus.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.menus.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span class="ml-3">Menu Management</span>
                    </a>
                </li>
                
                <!-- Category Management -->
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1zM5.618 4.504a1 1 0 01-.372 1.364L5.016 6l.23.132a1 1 0 11-.992 1.736L4 7.723V8a1 1 0 01-2 0V6a.996.996 0 01.52-.878l1.734-.99a1 1 0 011.364.372zm8.764 0a1 1 0 011.364-.372l1.733.99A1.002 1.002 0 0118 6v2a1 1 0 11-2 0v-.277l-.254.145a1 1 0 11-.992-1.736l.23-.132-.23-.132a1 1 0 01-.372-1.364zm-7 4a1 1 0 011.364-.372L10 8.848l1.254-.716a1 1 0 11.992 1.736L11 10.58V12a1 1 0 11-2 0v-1.42l-1.246-.712a1 1 0 01-.372-1.364zM3 11a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm10-1a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Category Management</span>
                    </a>
                </li>

                {{-- Number Tables Management --}}
                <li>
                    <a href="{{ route('admin.tables.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.tables.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
                        </svg>
                        <span class="ml-3">Table Management</span>
                    </a>
                </li>

                {{-- Crew Management --}}
                <li>
                    <a href="{{ route('admin.crews.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.crews.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z"></path>
                        </svg>
                        <span class="ml-3">Crew Management</span>
                    </a>
                </li>

                {{-- Customer Management --}}
                <li>
                    <a href="{{ route('admin.blacklist.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('admin.blacklist.index') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Customer Blacklist</span>
                    </a>
                </li>
                
            @elseif($role === 'cashier')
                <!-- Cashier Dashboard -->
                <li>
                    <a href="{{ route('cashier.dashboard') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('cashier.dashboard') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                
                <!-- Cashier Orders -->
                <li>
                    <a href="{{ route('cashier.orders.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('cashier.orders.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Order Management</span>
                    </a>
                </li>

                <!-- Cashier Payments -->
                <li>
                    <a href="{{ route('cashier.payments.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('cashier.payments.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Payment Management</span>
                    </a>
                </li>

                <!-- Cashier Reservations -->
                <li>
                    <a href="{{ route('cashier.reservations.index') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('cashier.reservations.*') ? 'bg-gray-100' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Reservation Management</span>
                    </a>
                </li>
                
            @endif
        </ul>

        <!-- Bottom section with user profile and logout -->
        @auth
        <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200">
            <li>
                <a href="{{ route('profile.edit') }}" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 group">
                    <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Profile</span>
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                       class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 group">
                        <svg class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-3">Log Out</span>
                    </a>
                </form>
            </li>
        </ul>
        @endauth
    </div>
</aside>