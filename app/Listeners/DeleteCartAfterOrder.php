<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class DeleteCartAfterOrder
{
    public function handle(OrderStatusUpdated $event)
{
    try {
        if ($event->oldStatus !== 'completed' && $event->newStatus === 'completed') {
            $cartCount = Cart::where('order_id', $event->order->id)->count();
            
            Log::info('Order completed. Cart items would be deleted for order: ' . 
                     $event->order->id . ', Count: ' . $cartCount . 
                     ' (But deletion is disabled for data preservation)');
        }
    } catch (\Exception $e) {
        Log::error('Error in DeleteCartAfterOrder: ' . $e->getMessage());
    }
}
}