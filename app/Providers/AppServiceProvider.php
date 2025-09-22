<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Reservation;
use App\Observers\ReservationObserver;
use App\Events\ReservationStatusUpdated;
use App\Listeners\UpdateRelatedOrdersStatus;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Reservation::observe(ReservationObserver::class);
        Event::listen(
            ReservationStatusUpdated::class,
            UpdateRelatedOrdersStatus::class
        );
        
    }
}
