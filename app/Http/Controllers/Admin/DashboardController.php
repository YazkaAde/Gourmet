<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?: 0;
        $pendingReplies = Review::whereNull('admin_reply')->count();
        $repliedReviews = Review::whereNotNull('admin_reply')->count();
        
        $totalOrders = Order::count();
        $totalUsers = User::where('role', 'customer')->count();

        return view('admin.dashboard', compact(
            'totalReviews',
            'averageRating',
            'pendingReplies',
            'repliedReviews',
            'totalOrders',
            'totalUsers'
        ));
    }
}