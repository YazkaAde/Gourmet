<?php

namespace App\Listeners;

use App\Events\ReservationStatusUpdated;
use App\Models\Order;

class UpdateRelatedOrdersStatus
{
    public function handle(ReservationStatusUpdated $event)
    {
        $reservation = $event->reservation;
        
        if ($reservation->status === 'completed' && $reservation->orders()->exists()) {
            $reservation->orders()->update(['status' => 'completed']);
        }
    }
}