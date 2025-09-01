<?php

namespace App\Providers;

use App\Events\OrderStatusUpdated;
use App\Events\PaymentStatusUpdated;
use Illuminate\Auth\Events\Registered;
use App\Listeners\DeleteCartAfterOrder;
use App\Listeners\DeleteOrderAfterPayment;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderStatusUpdated::class => [
            DeleteCartAfterOrder::class,
        ],
        PaymentStatusUpdated::class => [
            DeleteOrderAfterPayment::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}