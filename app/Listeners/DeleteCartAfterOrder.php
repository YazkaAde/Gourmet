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
                $deletedCount = Cart::where('order_id', $event->order->id)->delete();
                
                Log::info('Cart items deleted for order: ' . $event->order->id . ', Count: ' . $deletedCount);
            }
        } catch (\Exception $e) {
            Log::error('Error in DeleteCartAfterOrder: ' . $e->getMessage());
        }
    }
}