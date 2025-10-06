<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Support\Facades\Event;
use App\Observers\ReservationObserver;
use Illuminate\Support\ServiceProvider;
use App\Events\ReservationStatusUpdated;
use App\Listeners\UpdateRelatedOrdersStatus;

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
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
        Carbon::setToStringFormat('Y-m-d H:i:s');
        Reservation::observe(ReservationObserver::class);
        Event::listen(
            ReservationStatusUpdated::class,
            UpdateRelatedOrdersStatus::class
        );
        
    }
}
