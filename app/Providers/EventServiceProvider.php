<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\OrderStatusUpdated;
use App\Events\PaymentStatusUpdated;
use App\Listeners\DeleteCartAfterOrder;
use App\Listeners\DeleteOrderAfterPayment;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerification::class,
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