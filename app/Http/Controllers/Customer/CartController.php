<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = auth()->user()->carts()->with('menu')->get();
        return view('customer.cart', compact('carts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = auth()->user()->carts()->firstOrCreate(
            ['menu_id' => $request->menu_id],
            ['quantity' => 0]
        );

        $cart->increment('quantity', $request->quantity);

        return response()->json([
            'success' => true,
            'totalItems' => auth()->user()->carts->sum('quantity')
        ]);
    }

    public function destroy($id)
    {
        $cart = auth()->user()->carts()->findOrFail($id);
        $cart->delete();

        return back()->with('success', 'Item removed from cart');
    }
}