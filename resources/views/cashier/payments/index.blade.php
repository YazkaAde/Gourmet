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
                                                <a href="{{ route('cashier.payments.show', $payment) }}" 
                                                class="text-primary-600 hover:text-primary-800 text-sm">
                                                    View
                                                </a>
                                                
                                                @if($payment->status == 'pending')
                                                    @if($payment->payment_method != 'cash')
                                                        <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="text-green-600 hover:text-green-800 text-sm"
                                                                    onclick="return confirm('Confirm this {{ $payment->payment_method }} payment?')">
                                                                Confirm
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button type="button" 
                                                                onclick="confirmCashPayment({{ $payment->id }}, {{ $payment->amount }})"
                                                                class="text-green-600 hover:text-green-800 text-sm">
                                                            Confirm
                                                        </button>
                                                        
                                                        <form action="{{ route('cashier.payments.confirm', $payment) }}" method="POST" id="cashForm{{ $payment->id }}" class="hidden">
                                                            @csrf
                                                            <input type="number" name="amount_paid" id="amountInput{{ $payment->id }}">
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('cashier.payments.reject', $payment) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-800 text-sm"
                                                                onclick="return confirm('Reject this payment?')">
                                                            Reject
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($payment->status == 'paid')
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

    <div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg w-96 p-6">
            <h3 class="text-lg font-medium mb-4" id="modalTitle">Confirm Payment</h3>
            <form id="confirmationForm" method="POST">
                @csrf
                <div id="cashFields" class="mb-4 hidden">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount Paid</label>
                        <input type="number" 
                               name="amount_paid" 
                               class="w-full p-2 border border-gray-300 rounded"
                               placeholder="Enter amount paid"
                               min="0"
                               required
                               step="500"
                               id="amountPaidInput">
                        <p class="text-sm text-gray-500 mt-1">Amount due: <span id="amountDue">Rp 0</span></p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change</label>
                        <div class="p-3 bg-gray-100 rounded">
                            <p class="font-medium" id="changeAmount">Rp 0</p>
                        </div>
                    </div>
                </div>
                
                <div id="nonCashMessage" class="mb-4 p-3 bg-blue-50 rounded hidden">
                    <p class="text-sm text-blue-700">Confirm this payment? Payment will be marked as paid.</p>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeConfirmationModal()" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let currentPaymentId = null;

    function openConfirmationModal(paymentId, paymentMethod, amountDue) {
        currentPaymentId = paymentId;
        const modal = document.getElementById('confirmationModal');
        const cashFields = document.getElementById('cashFields');
        const nonCashMessage = document.getElementById('nonCashMessage');
        const amountDueSpan = document.getElementById('amountDue');
        const form = document.getElementById('confirmationForm');
        
        form.action = `/cashier/payments/${paymentId}/confirm`;
        
        amountDueSpan.textContent = `Rp ${amountDue.toLocaleString()}`;
        
        if (paymentMethod === 'cash') {
            cashFields.classList.remove('hidden');
            nonCashMessage.classList.add('hidden');
            document.getElementById('amountPaidInput').value = '';
            document.getElementById('changeAmount').textContent = 'Rp 0';
        } else {
            cashFields.classList.add('hidden');
            nonCashMessage.classList.remove('hidden');
        }
        
        modal.classList.remove('hidden');
        if (paymentMethod === 'cash') {
            document.getElementById('amountPaidInput').focus();
        }
    }

    function closeConfirmationModal() {
        document.getElementById('confirmationModal').classList.add('hidden');
    }

    document.getElementById('amountPaidInput')?.addEventListener('input', function() {
        const amountPaid = parseFloat(this.value) || 0;
        const amountDue = parseFloat(document.getElementById('amountDue').textContent.replace(/[^\d]/g, '')) || 0;
        const change = amountPaid - amountDue;

        document.getElementById('changeAmount').textContent = 
            `Rp ${change >= 0 ? change.toLocaleString() : '0'}`;
    });

    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmationModal();
        }
    });
    </script>
</x-app-layout>