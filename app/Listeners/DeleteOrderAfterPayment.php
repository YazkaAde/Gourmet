<?php

namespace App\Listeners;

use App\Events\PaymentStatusUpdated;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class DeleteOrderAfterPayment
{
    public function handle(PaymentStatusUpdated $event)
    {
        try {
            if ($event->oldStatus !== 'paid' && $event->newStatus === 'paid') {
                $deleted = Order::where('id', $event->payment->order_id)->delete();
                
                if ($deleted) {
                    Log::info('Order deleted after payment: ' . $event->payment->order_id);
                } else {
                    Log::warning('Order not found for deletion: ' . $event->payment->order_id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error in DeleteOrderAfterPayment: ' . $e->getMessage());
        }
    }
}