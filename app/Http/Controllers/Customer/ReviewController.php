<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Order $order, Menu $menu)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403);
        }
    
        if (!$order->payment || $order->payment->status !== 'paid') {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'Please complete payment first before reviewing.');
        }
    
        $existingReview = Review::where('user_id', Auth::id())
            ->where('menu_id', $menu->id)
            ->where('order_id', $order->id)
            ->first();
    
        if ($existingReview) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You have already reviewed this menu for this order.');
        }
    
        $menuInOrder = $order->carts()->where('menu_id', $menu->id)->exists();
        if (!$menuInOrder) {
            abort(403, 'Menu not found in this order');
        }
    
        return view('customer.reviews.create', compact('order', 'menu'));
    }
    
    public function store(Request $request, Order $order, Menu $menu)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403);
        }
    
        if (!$order->payment || $order->payment->status !== 'paid') {
            return redirect()->back()->with('error', 'Please complete payment first before reviewing.');
        }
    
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);
    
        $existingReview = Review::where('user_id', Auth::id())
            ->where('menu_id', $menu->id)
            ->where('order_id', $order->id)
            ->first();
    
        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this menu for this order.');
        }
    
        Review::create([
            'user_id' => Auth::id(),
            'menu_id' => $menu->id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
    
        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Review submitted successfully!');
    }
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }
}