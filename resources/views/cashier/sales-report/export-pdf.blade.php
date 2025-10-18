<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header .subtitle {
            font-size: 14px;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .summary-item {
            padding: 8px;
        }
        .summary-label {
            font-weight: bold;
            color: #333;
        }
        .summary-value {
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-completed, .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .status-cancelled, .status-failed, .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .revenue-summary {
            background-color: #e8f5e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Generated on: {{ $exportDate }}</div>
    </div>

    <div class="summary">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Orders:</div>
                <div class="summary-value">{{ number_format($totalOrders) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Paid Revenue:</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Report Period:</div>
                <div class="summary-value">{{ ucfirst($type) }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Orders in Period:</div>
                <div class="summary-value">{{ number_format($orders->count()) }}</div>
            </div>
        </div>
    </div>

    <div class="revenue-summary">
        <strong>Note:</strong> Revenue calculation only includes payments with status 'Paid'
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Table</th>
                <th class="text-right">Amount</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->table_number ?? 'N/A' }}</td>
                <td class="text-right">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    <span class="status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    @if($order->payment)
                        <span class="status-{{ $order->payment->status }}">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    @else
                        <span class="status-unpaid">Unpaid</span>
                    @endif
                </td>
                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($orders->count() > 0)
    <div class="summary">
        <strong>Summary:</strong> Showing {{ $orders->count() }} orders with <strong>paid revenue</strong> of Rp {{ number_format($totalRevenue, 0, ',', '.') }}
    </div>
    @endif

    <div class="footer">
        This report was generated automatically by Restaurant Management System
    </div>
</body>
</html>