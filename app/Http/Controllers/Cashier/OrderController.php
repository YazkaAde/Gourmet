<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use Illuminate\Http\Request;

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
        'status' => 'required|in:pending,processing,completed,cancelled'
    ]);

    $oldStatus = $order->status;
    $newStatus = $request->status;
    
    $allowedTransitions = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['completed', 'cancelled'],
        'cancelled' => ['pending', 'processing'],
        'completed' => []
    ];
    
    if (!in_array($newStatus, $allowedTransitions[$oldStatus])) {
        return redirect()->back()->with('error', 'Status transition not allowed.');
    }
    
    $order->update(['status' => $newStatus]);

    event(new OrderStatusUpdated($order, $oldStatus, $newStatus));

    return redirect()->back()->with('success', 'Order status updated successfully.');
}
}