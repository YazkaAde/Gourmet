<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $payment->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="receipt-container max-w-md mx-auto bg-white p-6 shadow-lg">
        <!-- Print Button -->
        <div class="no-print mb-6 text-center">
            <button onclick="window.print()" 
                    class="px-6 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 transition">
                Print Receipt
            </button>
            <a href="{{ route('cashier.payments.show', $payment) }}"
               class="ml-3 px-6 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                Back to Payment
            </a>
        </div>

        <!-- Receipt Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">RESTAURANT RECEIPT</h1>
            <p class="text-gray-600">Sumampir, Purwokerto Utara</p>
            <p class="text-gray-600">Phone: (+62) 0828 yg lainnya kapan-kapan</p>
        </div>

        <!-- Receipt Details -->
        <div class="border-b border-gray-300 pb-4 mb-4">
            <div class="grid grid-cols-2 gap-2 text-sm">
                <div>
                    <span class="font-medium">Receipt #:</span> {{ $payment->id }}
                </div>
                <div class="text-right">
                    <span class="font-medium">Date:</span> {{ $payment->updated_at->format('M d, Y') }}
                </div>
                <div>
                    <span class="font-medium">Order #:</span> {{ $payment->order_id }}
                </div>
                <div class="text-right">
                    <span class="font-medium">Time:</span> {{ $payment->updated_at->format('H:i') }}
                </div>
                <div>
                    <span class="font-medium">Cashier:</span> {{ Auth::user()->name }}
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="border-b border-gray-300 pb-4 mb-4">
            <h3 class="font-semibold mb-2">CUSTOMER INFORMATION</h3>
            <div class="text-sm">
                @if($payment->order_id && $payment->order && $payment->order->user)
                    <p><span class="font-medium">Name:</span> {{ $payment->order->user->name }}</p>
                    <p><span class="font-medium">Table:</span> {{ $payment->order->table_number ?? 'N/A' }}</p>
                @elseif($payment->reservation_id && $payment->reservation && $payment->reservation->user)
                    <p><span class="font-medium">Name:</span> {{ $payment->reservation->user->name }}</p>
                    <p><span class="font-medium">Table:</span> {{ $payment->reservation->table_number ?? 'N/A' }}</p>
                    <p><span class="font-medium">Time:</span> {{ $payment->reservation->reservation_time }} - {{ $payment->reservation->end_time }}</p>
                @else
                    <p><span class="font-medium">Name:</span> Customer information not available</p>
                @endif
            </div>
        </div>

        {{-- Order Items --}}
        @if($payment->order_id && $payment->order && $payment->order->orderItems)
        <div class="border-b border-gray-300 pb-4 mb-4">
            <h3 class="font-semibold mb-3">ORDER ITEMS</h3>
            <div class="space-y-2 text-sm">
                @foreach($payment->order->orderItems as $orderItem)
                @if($orderItem->menu)
                <div class="flex justify-between">
                    <div>
                        <span class="font-medium">{{ $orderItem->quantity }}x</span> {{ $orderItem->menu->name }}
                    </div>
                    <div>Rp {{ number_format($orderItem->price * $orderItem->quantity, 0) }}</div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Payment Summary -->
        <div class="border-b border-gray-300 pb-4 mb-4">
            <h3 class="font-semibold mb-3">PAYMENT SUMMARY</h3>
            <div class="space-y-1 text-sm">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($payment->amount, 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Payment Method:</span>
                    <span class="font-medium">{{ strtoupper(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                
                @if($payment->payment_method == 'cash')
                <div class="flex justify-between">
                    <span>Amount Paid:</span>
                    <span>Rp {{ number_format($payment->amount_paid, 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Change:</span>
                    <span>Rp {{ number_format($payment->change, 0) }}</span>
                </div>
                @endif
                
                <div class="flex justify-between text-lg font-bold mt-2">
                    <span>TOTAL:</span>
                    <span>Rp {{ number_format($payment->amount, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Status -->
        <div class="border-b border-gray-300 pb-4 mb-4">
            <div class="text-center">
                <span class="px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    PAYMENT {{ strtoupper($payment->status) }}
                </span>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-xs text-gray-600">
            <p>Thank you for dining with us!</p>
            <p>*** This is a computer generated receipt ***</p>
            <p class="mt-2">Receipt generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>