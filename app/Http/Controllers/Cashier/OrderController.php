<?php

namespace App\Http\Controllers\Cashier;

use App\Models\Order;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Events\OrderStatusUpdated;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $status = request('status');
        
        $orders = Order::with(['user', 'orderItems.menu'])
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', direction: 'desc')
            ->paginate(10);
            
        return view('cashier.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.menu', 'table']);
        
        return view('cashier.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        if ($order->reservation_id) {
            $reservation = Reservation::find($order->reservation_id);
            
            if ($reservation && $reservation->shouldBeCompleted()) {
                $reservation->update(['status' => 'completed']);
            }
        }

        return back()->with('success', 'Order status updated successfully.');
    }

    public function getOrdersCount()
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        
        $recentOrders = Order::with(['user', 'payment'])
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer' => $order->user->name,
                    'table' => $order->table_number,
                    'amount' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('H:i')
                ];
            });

        return response()->json([
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'recentOrders' => $recentOrders,
            'lastUpdate' => now()->toISOString()
        ]);
    }
}