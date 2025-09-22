<?php

namespace App\Providers;

use App\Events\OrderCompleted;
use App\Events\OrderStatusUpdated;
use App\Events\PaymentStatusUpdated;
use Illuminate\Auth\Events\Registered;
use App\Listeners\DeleteCartAfterOrder;
use App\Listeners\UpdateReservationStatus;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
        OrderCompleted::class  => [
            UpdateReservationStatus::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}