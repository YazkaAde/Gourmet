<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\Order;
use App\Models\OrderItem;

class ReservationObserver
{
    public function retrieved(Reservation $reservation)
    {
        $reservation->checkAndUpdateStatus();
    }
    public function updated(Reservation $reservation)
    {
        if ($reservation->status === 'confirmed' && !$reservation->hasOrder()) {
            $this->createOrderFromReservation($reservation);
        }
        
        if ($reservation->status === 'completed' && $reservation->orders()->exists()) {
            $reservation->orders()->update(['status' => 'completed']);
        }
    }
    
    private function createOrderFromReservation(Reservation $reservation)
    {
        $menuTotal = $reservation->orderItems()->sum('total_price');
        
        $order = Order::create([
            'user_id' => $reservation->user_id,
            'reservation_id' => $reservation->id,
            'table_number' => $reservation->table_number,
            'total_price' => $menuTotal,
            'status' => 'pending'
        ]);
        
        OrderItem::where('reservation_id', $reservation->id)
                ->whereNull('order_id')
                ->update(['order_id' => $order->id]);
        
        return $order;
    }
}