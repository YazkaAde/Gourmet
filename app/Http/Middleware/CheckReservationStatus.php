<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class CheckReservationStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();
            
            Reservation::where('user_id', $userId)
                ->whereIn('status', ['pending', 'confirmed'])
                ->chunk(100, function ($reservations) {
                    foreach ($reservations as $reservation) {
                        try {
                            $reservation->checkAndUpdateStatus();
                        } catch (\Exception $e) {
                            \Log::error('Error updating reservation status in middleware', [
                                'reservation_id' => $reservation->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                });
        }
        
        return $next($request);
    }

    public function checkAndUpdateStatus()
    {
        try {
            if ($this->shouldBeCompleted() && $this->status !== 'completed') {
                $this->update(['status' => 'completed']);
                
                if ($this->orders()->exists()) {
                    $this->orders()->update(['status' => 'completed']);
                }
                
                return true;
            }
        } catch (\Exception $e) {
            \Log::error('Error in checkAndUpdateStatus', [
                'reservation_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
        
        return false;
    }
}