<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Filter Status -->
                    <div class="mb-6 flex gap-2 flex-wrap">
                        <a href="{{ route('cashier.payments.index') }}" 
                           class="px-4 py-2 rounded {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            All Payments
                        </a>
                        <a href="{{ route('cashier.payments.index', ['status' => 'pending']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Pending
                        </a>
                        <a href="{{ route('cashier.payments.index', ['status' => 'paid']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'paid' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Paid
                        </a>
                        <a href="{{ route('cashier.payments.index', ['status' => 'failed']) }}" 
                           class="px-4 py-2 rounded {{ request('status') == 'failed' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                            Failed
                        </a>
                    </div>

                    <!-- Payments Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Payment ID
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Type
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Customer
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Method
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Amount
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
                                @forelse($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            #{{ $payment->id }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            @if($payment->order_id)
                                                Order #{{ $payment->order_id }}
                                            @elseif($payment->reservation_id)
                                                Reservation #{{ $payment->reservation_id }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            @if($payment->order_id && $payment->order && $payment->order->user)
                                                {{ $payment->order->user->name }}
                                            @elseif($payment->reservation_id && $payment->reservation && $payment->reservation->user)
                                                {{ $payment->reservation->user->name }}
                                            @else
                                                Customer Not Found
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            Rp {{ number_format($payment->amount, 0) }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                                @if($payment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($payment->status == 'paid') bg-green-100 text-green-800
                                                @elseif($payment->status == 'failed') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            {{ $payment->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-300">
                                            <div class="flex gap-2">
                                                <!-- Tombol View selalu tersedia -->
                                                <a href="{{ route('cashier.payments.show', $payment) }}" 
                                                   class="text-primary-600 hover:text-primary-800 text-sm">
                                                    View
                                                </a>
                                                
                                                @if($payment->status == 'pending')
                                                    <!-- Tombol Confirm untuk payment pending -->
                                                    <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-800 text-sm"
                                                                onclick="return confirm('Confirm this payment?')">
                                                            Confirm
                                                        </button>
                                                    </form>
                                                    
                                                    @if($payment->payment_method == 'cash')
                                                    <!-- Tombol Process Cash untuk payment cash yang pending -->
                                                    <a href="{{ route('cashier.payments.show', $payment) }}" 
                                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                                        Process Cash
                                                    </a>
                                                    @endif
                                                @endif
                                                
                                                @if($payment->status == 'paid')
                                                    <!-- Tombol Receipt untuk payment yang sudah paid -->
                                                    <a href="{{ route('cashier.payments.receipt', $payment) }}" 
                                                       target="_blank"
                                                       class="text-purple-600 hover:text-purple-800 text-sm">
                                                        Receipt
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No payments found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $payments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>