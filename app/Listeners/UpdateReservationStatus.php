<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Models\Reservation;

class UpdateReservationStatus
{
    public function handle(OrderCompleted $event)
    {
        $order = $event->order;
        
        if ($order->reservation_id) {
            $reservation = Reservation::find($order->reservation_id);
            
            // Cek jika semua order dari reservasi ini sudah completed
            $allOrdersCompleted = $reservation->orders()
                ->where('status', '!=', 'completed')
                ->doesntExist();
            
            if ($allOrdersCompleted) {
                $reservation->update(['status' => 'completed']);
            }
        }
    }
}