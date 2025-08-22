<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();
        
        return DB::transaction(function () use ($user, $request) {
            // Hitung total harga dari semua item di cart
            $totalPrice = Cart::where('user_id', $user->id)
                ->whereNull('order_id')
                ->with('menu')
                ->get()
                ->sum(function ($cart) {
                    return $cart->menu->price * $cart->quantity;
                });
            
            // Buat order baru
            $order = Order::create([
                'user_id' => $user->id,
                'table_number' => $request->table_number, // atau dari reservation
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
            
            // Hubungkan cart items dengan order
            Cart::where('user_id', $user->id)
                ->whereNull('order_id')
                ->update(['order_id' => $order->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->id
            ]);
        });
    }
    
    public function index()
    {
        $user = Auth::user();
        $orders = Order::with(['carts.menu'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('customer.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Authorization check - pastikan user hanya bisa melihat order miliknya
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load(['carts.menu', 'reservation', 'table']);
        
        return view('customer.orders.show', compact('order'));
    }
}