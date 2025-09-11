<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $status = request('status');
        
        $reservations = Reservation::with('user')
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        return view('cashier.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'table', 'orders.carts.menu', 'payments', 'preOrderItems.menu');
        
        return view('cashier.reservations.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:cancelled',
        ]);

        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Reservation has been cancelled.');
    }
}