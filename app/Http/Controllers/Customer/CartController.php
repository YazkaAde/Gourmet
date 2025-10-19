<?php

namespace App\Http\Controllers\Customer;

use App\Models\Cart;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
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
        
        if ($menu->status !== 'available') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This menu item is currently unavailable'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'This menu item is currently unavailable!');
        }
    
        $totalPrice = $menu->price * $request->quantity;
    
        $cart = auth()->user()->carts()
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($cart) {
            if ($cart->order_id) {
                Cart::create([
                    'user_id' => auth()->id(),
                    'menu_id' => $request->menu_id,
                    'quantity' => $request->quantity,
                    'price' => $totalPrice,
                    'order_id' => null
                ]);
            } else {
                $cart->update([
                    'quantity' => $cart->quantity + $request->quantity,
                    'price' => $cart->price + $totalPrice
                ]);
            }
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
        
        return redirect()->back()->with('success', 'Item added to cart!');
    }

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

    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'cart_ids' => 'required|array',
            'cart_ids.*' => 'exists:carts,id'
        ]);

        auth()->user()->carts()
            ->whereNull('order_id')
            ->whereIn('id', $request->cart_ids)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected items removed from cart'
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'table_number' => 'required|exists:number_tables,table_number',
            'selected_cart_ids' => 'sometimes|array',
            'selected_cart_ids.*' => 'exists:carts,id'
        ]);

        $user = Auth::user();
        
        return DB::transaction(function () use ($user, $request) {
            $cartQuery = Cart::where('user_id', $user->id)
                ->whereNull('order_id')
                ->with('menu');
            
            if ($request->has('selected_cart_ids') && !empty($request->selected_cart_ids)) {
                $cartQuery->whereIn('id', $request->selected_cart_ids);
            }
            
            $cartItems = $cartQuery->get();
            
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items selected for checkout'
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
            
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $cartItem->menu_id,
                    'reservation_id' => null,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->menu->price,
                    'total_price' => $cartItem->menu->price * $cartItem->quantity
                ]);
            }
            
            if ($request->has('selected_cart_ids') && !empty($request->selected_cart_ids)) {
                Cart::where('user_id', $user->id)
                    ->whereNull('order_id')
                    ->whereIn('id', $request->selected_cart_ids)
                    ->delete();
            } else {
                Cart::where('user_id', $user->id)
                    ->whereNull('order_id')
                    ->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_id' => $order->id,
                'cart_cleared' => true
            ]);
        });
    }
}