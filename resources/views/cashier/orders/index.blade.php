<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter Status -->
                    <div class="mb-6 flex gap-2 flex-wrap">
                        <a href="{{ route('cashier.orders.index') }}" 
                           class="px-4 py-2 rounded {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            All Orders
                        </a>
                        <a href="{{ route('cashier.orders.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Pending
                        </a>
                        <a href="{{ route('cashier.orders.index', ['status' => 'processing']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'processing' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Processing
                        </a>
                        <a href="{{ route('cashier.orders.index', ['status' => 'completed']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'completed' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Completed
                        </a>
                    </div>

                    <!-- Orders Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Order ID
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Table
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            {{ $order->user->name }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                @if($order->order_type == 'dine_in') bg-blue-100 text-blue-800
                                                @else bg-orange-100 text-orange-800
                                                @endif">
                                                {{ str_replace('_', ' ', ucfirst($order->order_type)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            @if($order->order_type == 'dine_in')
                                                {{ $order->table_number ?? 'N/A' }}
                                            @else
                                                <span class="text-gray-500">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            Rp {{ number_format($order->total_price, 0) }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            {{ $order->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <div class="flex gap-2">
                                                @if($order->status == 'cancelled')
                                                    <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">
                                                            Uncancel
                                                        </button>
                                                    </form>
                                                @elseif($order->status != 'completed')
                                                    <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ 
                                                            $order->status == 'pending' ? 'processing' : 'completed'
                                                        }}">
                                                        <button type="submit" class="text-blue-600 hover:text-blue-800 text-sm">
                                                            {{ $order->status == 'pending' ? 'Process' : 'Complete' }}
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('cashier.orders.update-status', $order) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-500 text-sm">No actions</span>
                                                @endif
                                                
                                                <a href="{{ route('cashier.orders.show', $order) }}" 
                                                   class="text-primary-600 hover:text-primary-800 text-sm">
                                                    View
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>