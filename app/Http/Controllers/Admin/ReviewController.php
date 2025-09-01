<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Menu;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'menu'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_reviews' => Review::count(),
            'average_rating' => Review::avg('rating') ?: 0,
            'pending_replies' => Review::pendingReply()->count(),
            'rating_distribution' => Review::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get()
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'menu', 'order']);

        return view('admin.reviews.show', compact('review'));
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:500'
        ]);

        $review->update([
            'admin_reply' => $request->admin_reply,
            'replied_at' => now()
        ]);

        return redirect()->back()->with('success', 'Reply submitted successfully!');
    }

    public function menuStats(Menu $menu)
    {
        $menu->load('reviews.user');

        $stats = [
            'average_rating' => $menu->average_rating,
            'rating_count' => $menu->rating_count,
            'rating_distribution' => $menu->rating_distribution
        ];

        return view('admin.reviews.menu-stats', compact('menu', 'stats'));
    }
}