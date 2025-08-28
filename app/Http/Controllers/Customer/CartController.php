<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
{
    $carts = auth()->user()->carts()
        ->whereNull('order_id')
        ->with('menu')
        ->get();
    
    if (request()->wantsJson()) {
        $cartHtml = view('partials.cart-items', compact('carts'))->render();
        
        return response()->json([
            'cart_html' => $cartHtml
        ]);
    }
    
    $menus = Menu::all();
    return view('customer.cart', compact('carts', 'menus'));
}

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        $totalPrice = $menu->price * $request->quantity;

        $cart = auth()->user()->carts()
            ->where('menu_id', $request->menu_id)
            ->whereNull('order_id')
            ->first();
            

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $request->quantity,
                'price' => $cart->price + $totalPrice
            ]);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'menu_id' => $request->menu_id,
                'quantity' => $request->quantity,
                'price' => $totalPrice,
                'order_id' => null
            ]);
        }
        
        $totalQuantity = auth()->user()->carts()
        ->whereNull('order_id')
        ->sum('quantity');
    
    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'total_quantity' => $totalQuantity
        ]);
    }
    
    return redirect()->back()->with('success', 'Item added to cart!');    }

    public function update(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $cart = auth()->user()->carts()
        ->whereNull('order_id')
        ->findOrFail($id);

    $menu = Menu::findOrFail($cart->menu_id);
    $newPrice = $menu->price * $request->quantity;

    $cart->update([
        'quantity' => $request->quantity,
        'price' => $newPrice
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Cart updated successfully',
        'new_total' => $newPrice
    ]);
}

    public function destroy($id)
    {
        $cart = auth()->user()->carts()
            ->whereNull('order_id')
            ->findOrFail($id);
            
        $cart->delete();

        return back()->with('success', 'Item removed from cart');
    }

    // Method checkout
    public function checkout(Request $request)
{
    $request->validate([
        'table_number' => 'required|exists:number_tables,table_number'
    ]);

    $user = Auth::user();
    
    return DB::transaction(function () use ($user, $request) {
        $cartItems = Cart::where('user_id', $user->id)
            ->whereNull('order_id')
            ->with('menu')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }
        
        $tableInUse = Order::where('table_number', $request->table_number)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();
            
        if ($tableInUse) {
            return response()->json([
                'success' => false,
                'message' => 'Table is currently occupied'
            ], 400);
        }
        
        $totalPrice = $cartItems->sum(function ($cart) {
            return $cart->menu->price * $cart->quantity;
        });
        
        $order = Order::create([
            'user_id' => $user->id,
            'table_number' => $request->table_number,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);
        
        Cart::where('user_id', $user->id)
            ->whereNull('order_id')
            ->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully',
            'order_id' => $order->id,
            'cart_cleared' => true
        ]);
    });
}
}