<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['carts.menu', 'payment'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $menuIds = collect();
        foreach ($orders as $order) {
            foreach ($order->carts as $cart) {
                $menuIds->push($cart->menu_id);
            }
        }
        
        $userReviews = Review::where('user_id', $user->id)
            ->whereIn('menu_id', $menuIds->unique())
            ->get()
            ->keyBy('menu_id');
        
        return view('customer.orders.index', compact('orders', 'userReviews'));
    }    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load([
            'carts.menu', 
            'carts.menu.reviews' => function($query) {
                $query->where('user_id', auth()->id());
            }, 
            'reservation', 
            'table', 
            'payment'
        ]);
        
        return view('customer.orders.show', compact('order'));
    }

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