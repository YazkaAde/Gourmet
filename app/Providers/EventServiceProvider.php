<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PaymentStatusUpdated;
use App\Events\OrderStatusUpdated;
// Hapus impor jika file listener dihapus
// use App\Listeners\DeleteOrderAfterPayment;
use App\Listeners\DeleteCartAfterOrder;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        PaymentStatusUpdated::class => [
        ],
        
        OrderStatusUpdated::class => [
            DeleteCartAfterOrder::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}