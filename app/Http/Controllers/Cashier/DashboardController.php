<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now();
        
        $todayOrders = Order::whereDate('created_at', $today->toDateString())->count();
        $todayRevenue = Order::whereDate('created_at', $today->toDateString())
            ->whereHas('payment', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('total_price');

        $pendingPayments = Payment::where('status', 'pending')->count();
        $paidPayments = Payment::where('status', 'paid')->count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();

        $todayReservations = Reservation::whereDate('reservation_date', $today->toDateString())->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();

        $recentOrders = Order::with(['user', 'payment'])
            ->whereDate('created_at', $today->toDateString())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['order.user', 'reservation.user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('cashier.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'pendingPayments',
            'paidPayments',
            'pendingOrders',
            'processingOrders',
            'todayReservations',
            'pendingReservations',
            'confirmedReservations',
            'recentOrders',
            'recentPayments'
        ));
    }

    public function getStats()
    {
        $today = now();
        
        $todayOrders = Order::whereDate('created_at', $today->toDateString())->count();
        $todayRevenue = Order::whereDate('created_at', $today->toDateString())
            ->whereHas('payment', function($query) {
                $query->where('status', 'paid');
            })
            ->sum('total_price');

        $pendingPayments = Payment::where('status', 'pending')->count();
        $paidPayments = Payment::where('status', 'paid')->count();

        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();

        $todayReservations = Reservation::whereDate('reservation_date', $today->toDateString())->count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $confirmedReservations = Reservation::where('status', 'confirmed')->count();

        return response()->json([
            'todayOrders' => $todayOrders,
            'todayRevenue' => $todayRevenue,
            'pendingPayments' => $pendingPayments,
            'paidPayments' => $paidPayments,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'todayReservations' => $todayReservations,
            'pendingReservations' => $pendingReservations,
            'confirmedReservations' => $confirmedReservations,
            'lastUpdate' => now()->toISOString()
        ]);
    }

}