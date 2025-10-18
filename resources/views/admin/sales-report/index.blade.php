<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales Report') }}
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

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-700">
                        <strong>Summary Cards:</strong> Menampilkan data sesuai dengan period yang dipilih. 
                        <strong>Table below:</strong> Menampilkan detail orders sesuai filter yang dipilih.
                    </p>
                </div>
            </div>

            <!-- Summary Cards - Hanya 2 Card sesuai Period -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Orders Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">
                                @if($type == 'all')
                                    Total Orders (All Time)
                                @else
                                    Total Orders ({{ ucfirst($type) }})
                                @endif
                            </p>
                            <p class="text-2xl font-bold">{{ $totalOrders ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-gradient-to-r from-green-500 to-green-600 text-white overflow-hidden shadow-lg rounded-xl p-6 transform hover:scale-105 transition duration-300">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-white bg-opacity-20 mr-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium opacity-90">
                                @if($type == 'all')
                                    Total Revenue (All Time)
                                @else
                                    {{ ucfirst($type) }} Revenue
                                @endif
                            </p>
                            <p class="text-2xl font-bold">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs opacity-80 mt-1">Only includes paid payments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Section -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-8">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">Export Sales Report</h3>
                            <p class="text-sm text-gray-600 mt-1">Download sales data in PDF format</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row gap-4 items-end">
                            <!-- Date Filter -->
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" 
                                           class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                    <input type="date" name="end_date" id="end_date" 
                                           class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <button type="button" onclick="applyFilter()" 
                                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300 h-10 mt-6">
                                    Apply Filter
                                </button>
                            </div>

                            <!-- Export Dropdown -->
                            <div class="relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">View & Export Period</label>
                                <div class="flex gap-2">
                                    <select id="exportType" onchange="changePeriod()" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2">
                                        <option value="all" {{ $type == 'all' ? 'selected' : '' }}>All Data</option>
                                        <option value="daily" {{ $type == 'daily' ? 'selected' : '' }}>Daily</option>
                                        <option value="weekly" {{ $type == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="monthly" {{ $type == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="yearly" {{ $type == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    <button onclick="exportReport()" 
                                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Export PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">
                            @if($type == 'all')
                                All Orders
                            @else
                                {{ ucfirst($type) }} Orders
                            @endif
                        </h3>
                        <span class="text-sm text-gray-500">Showing {{ $orders->count() }} orders</span>
                    </div>
                    
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($orders as $order)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $order->table_number ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->status === 'completed') bg-green-100 text-green-800
                                                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'pending') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->payment && in_array($order->payment->status, ['completed', 'paid'])) bg-green-100 text-green-800
                                                @elseif($order->payment && $order->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $order->payment ? ucfirst($order->payment->status) : 'Unpaid' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                            <p class="text-gray-500">No orders match your current filter.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function applyFilter() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            
            if (startDate && endDate) {
                window.location.href = `{{ route('admin.sales-report.filter') }}?start_date=${startDate}&end_date=${endDate}`;
            } else {
                alert('Please select both start and end dates.');
            }
        }
    
        function changePeriod() {
            const exportType = document.getElementById('exportType').value;
            window.location.href = `{{ route('admin.sales-report.index') }}?type=${exportType}`;
        }
    
        function exportReport() {
            const exportType = document.getElementById('exportType').value;
            window.location.href = `{{ route('admin.sales-report.export') }}?type=${exportType}`;
        }
    
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('end_date').value = today;
            
            const sevenDaysAgo = new Date();
            sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
            document.getElementById('start_date').value = sevenDaysAgo.toISOString().split('T')[0];
        });
    </script>
</x-app-layout>