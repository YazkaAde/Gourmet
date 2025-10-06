<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\Menu;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    
        $existingReview = Review::getUserReviewForMenu(Auth::id(), $menu->id);
    
        if ($existingReview) {
            return redirect()->route('customer.orders.show', $order)
                ->with('error', 'You have already reviewed this menu. You can only review each menu once.');
        }
    
        $menuInOrder = $order->orderItems()->where('menu_id', $menu->id)->exists();
        if (!$menuInOrder) {
            abort(403, 'Menu not found in this order');
        }
    
        return view('customer.reviews.create', compact('order', 'menu'));
    }

    public function createFromReservation(Reservation $reservation, Menu $menu)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->canBeReviewed()) {
            abort(403);
        }
    
        $existingReview = Review::getUserReviewForMenu(Auth::id(), $menu->id);
    
        if ($existingReview) {
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('error', 'You have already reviewed this menu. You can only review each menu once.');
        }
    
        $menuInReservation = $reservation->orderItems()->where('menu_id', $menu->id)->exists();
        if (!$menuInReservation) {
            abort(403, 'Menu not found in this reservation');
        }
    
        return view('customer.reviews.create-from-reservation', compact('reservation', 'menu'));
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
    
        try {
            DB::transaction(function () use ($request, $order, $menu) {
                $existingReview = Review::getUserReviewForMenu(Auth::id(), $menu->id);
        
                if ($existingReview) {
                    throw new \Exception('You have already reviewed this menu. You can only review each menu once.');
                }
    
                Review::create([
                    'user_id' => Auth::id(),
                    'menu_id' => $menu->id,
                    'order_id' => $order->id,
                    'reservation_id' => null,
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);
            });
    
            return redirect()->route('customer.orders.show', $order)
                ->with('success', 'Review submitted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeFromReservation(Request $request, Reservation $reservation, Menu $menu)
    {
        if ($reservation->user_id !== Auth::id() || !$reservation->canBeReviewed()) {
            abort(403);
        }
    
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500'
        ]);
    
        try {
            DB::transaction(function () use ($request, $reservation, $menu) {
                $existingReview = Review::getUserReviewForMenu(Auth::id(), $menu->id);
        
                if ($existingReview) {
                    throw new \Exception('You have already reviewed this menu. You can only review each menu once.');
                }
    
                Review::create([
                    'user_id' => Auth::id(),
                    'menu_id' => $menu->id,
                    'order_id' => null,
                    'reservation_id' => $reservation->id,
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);
            });
    
            return redirect()->route('customer.reservations.show', $reservation)
                ->with('success', 'Review submitted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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