<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['carts.menu'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Menambahkan pagination
            
        return view('customer.orders', compact('orders')); // Mengubah ke customer.orders
    }
    
    public function show(Order $order)
    {
        // Authorization check - pastikan user hanya bisa melihat order miliknya
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['carts.menu', 'reservation', 'table']);
        
        return view('customer.order-show', compact('order')); // Akan membuat file terpisah nanti
    }

    // Method untuk membatalkan order
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa membatalkan order yang masih pending
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot cancel order that is already being processed.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('customer.orders.index')->with('success', 'Order cancelled successfully.');
    }
}