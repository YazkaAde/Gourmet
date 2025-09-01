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
        $orders = Order::with(['carts.menu', 'carts.menu.reviews' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        
        // Tambahkan eager loading untuk payment
        $orders->load('payment');
        
        return view('customer.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
{
    if ($order->user_id !== Auth::id()) {
        abort(403);
    }
    
    $order->load(['carts.menu', 'carts.menu.reviews' => function($query) {
        $query->where('user_id', auth()->id());
    }, 'reservation', 'table', 'payment']);
    
    return view('customer.orders.show', compact('order'));
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