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

        $existingReview = Review::where('user_id', Auth::id())
            ->where('menu_id', $menu->id)
            ->where('order_id', $order->id)
            ->first();

        return view('customer.reviews.create', compact('order', 'menu', 'existingReview'));
    }

    public function store(Request $request, Order $order, Menu $menu)
    {
        if ($order->user_id !== Auth::id() || $order->status !== 'completed') {
            abort(403);
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