<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cashier Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Today's Orders Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">Today's Orders</p>
                            <p class="text-2xl font-bold">{{ $todayOrders ?? 0 }}</p>
                            <p class="text-xs opacity-80 mt-1">Orders placed today</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Revenue Card -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">Today's Revenue</p>
                            <p class="text-2xl font-bold">Rp {{ number_format($todayRevenue ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs opacity-80 mt-1">Paid orders only</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments Card -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">Pending Payments</p>
                            <p class="text-2xl font-bold">{{ $pendingPayments ?? 0 }}</p>
                            <p class="text-xs opacity-80 mt-1">Waiting confirmation</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Reservations Card -->
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">Today's Reservations</p>
                            <p class="text-2xl font-bold">{{ $todayReservations ?? 0 }}</p>
                            <p class="text-xs opacity-80 mt-1">Scheduled for today</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Orders Quick Action -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Order Management</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Manage customer orders and update order status</p>
                        <div class="space-y-2">
                            <a href="{{ route('cashier.orders.index', ['status' => 'pending']) }}" 
                               class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                <span class="text-yellow-800 font-medium">Pending Orders</span>
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $pendingOrders ?? 0 }}
                                </span>
                            </a>
                            <a href="{{ route('cashier.orders.index', ['status' => 'processing']) }}" 
                               class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <span class="text-blue-800 font-medium">Processing Orders</span>
                                <span class="bg-blue-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $processingOrders ?? 0 }}
                                </span>
                            </a>
                        </div>
                        <a href="{{ route('cashier.orders.index') }}" 
                           class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Orders
                        </a>
                    </div>
                </div>

                <!-- Payments Quick Action -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Payment Management</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Confirm payments and process transactions</p>
                        <div class="space-y-2">
                            <a href="{{ route('cashier.payments.index', ['status' => 'pending']) }}" 
                               class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                <span class="text-yellow-800 font-medium">Pending Payments</span>
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $pendingPayments ?? 0 }}
                                </span>
                            </a>
                            <a href="{{ route('cashier.payments.index', ['status' => 'paid']) }}" 
                               class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <span class="text-green-800 font-medium">Paid Payments</span>
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $paidPayments ?? 0 }}
                                </span>
                            </a>
                        </div>
                        <a href="{{ route('cashier.payments.index') }}" 
                           class="mt-4 w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            View All Payments
                        </a>
                    </div>
                </div>

                <!-- Reservations Quick Action -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Reservation Management</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Manage table reservations and schedules</p>
                        <div class="space-y-2">
                            <a href="{{ route('cashier.reservations.index', ['status' => 'pending']) }}" 
                               class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                <span class="text-yellow-800 font-medium">Pending Reservations</span>
                                <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $pendingReservations ?? 0 }}
                                </span>
                            </a>
                            <a href="{{ route('cashier.reservations.index', ['status' => 'confirmed']) }}" 
                               class="flex items-center justify-between p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                                <span class="text-green-800 font-medium">Confirmed Reservations</span>
                                <span class="bg-green-500 text-white px-2 py-1 rounded-full text-sm">
                                    {{ $confirmedReservations ?? 0 }}
                                </span>
                            </a>
                        </div>
                        <a href="{{ route('cashier.reservations.index') }}" 
                           class="mt-4 w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            View All Reservations
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Recent Orders -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Orders</h3>
                            <a href="{{ route('cashier.orders.index') }}" 
                               class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All
                            </a>
                        </div>
                        
                        @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->user->name }} • Table {{ $order->table_number }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0) }}</p>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status == 'completed') bg-green-100 text-green-800
                                        @elseif($order->status == 'processing') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No recent orders</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Pending Payments -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Pending Payments</h3>
                            <a href="{{ route('cashier.payments.index', ['status' => 'pending']) }}" 
                               class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                View All
                            </a>
                        </div>
                        
                        @if($recentPayments->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPayments as $payment)
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Payment #{{ $payment->id }}</p>
                                        <p class="text-sm text-gray-600">
                                            @if($payment->order_id && $payment->order)
                                                {{ $payment->order->user->name }} • Order #{{ $payment->order_id }}
                                            @elseif($payment->reservation_id && $payment->reservation)
                                                {{ $payment->reservation->user->name }} • Reservation #{{ $payment->reservation_id }}
                                            @else
                                                Customer Not Found
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0) }}</p>
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        {{ ucfirst($payment->payment_method) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No pending payments</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sales Report Quick Access -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-semibold text-gray-800">Sales Reports</h3>
                            <p class="text-gray-600 mt-1">Generate and export sales reports in PDF format</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('cashier.sales-report.index') }}" 
                               class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Sales Report
                            </a>
                            <a href="{{ route('cashier.sales-report.export') }}?type=daily" 
                               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export Today
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>