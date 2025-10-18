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
        $menuReviews = Menu::withCount(['reviews as total_reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews as pending_replies' => function($query) {
                $query->whereNull('admin_reply');
            }])
            ->having('total_reviews', '>', 0)
            ->orderBy('total_reviews', 'desc')
            ->paginate(10);

        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?: 0;
        $pendingReplies = Review::whereNull('admin_reply')->count();
        $repliedReviews = Review::whereNotNull('admin_reply')->count();

        return view('admin.reviews.index', compact(
            'menuReviews',
            'totalReviews',
            'averageRating',
            'pendingReplies',
            'repliedReviews'
        ));
    }

    public function show($id)
    {
        $menu = Menu::with('category')->findOrFail($id);
        
        $reviews = Review::with(['user', 'menu'])
            ->where('menu_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $averageRating = Review::where('menu_id', $id)->avg('rating') ?: 0;

        return view('admin.reviews.show', compact('menu', 'reviews', 'averageRating'));
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:500'
        ]);

        try {
            $review->update([
                'admin_reply' => $request->admin_reply,
                'replied_at' => now()
            ]);

            return redirect()->back()->with('success', 'Reply posted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to post reply: ' . $e->getMessage());
        }
    }

    public function menuStats(Menu $menu)
    {
        $stats = Review::where('menu_id', $menu->id)
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        $ratingDistribution = Review::where('menu_id', $menu->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();

        return response()->json([
            'stats' => $stats,
            'rating_distribution' => $ratingDistribution
        ]);
    }
}