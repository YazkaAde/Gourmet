<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('user')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        return view('cashier.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'table', 'orders.carts.menu', 'payments');
        
        return view('cashier.reservations.show', compact('reservation'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Reservation status updated successfully.');
    }
}