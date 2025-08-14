<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklisted
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_blacklisted && Auth::user()->role === 'customer') {
            Auth::logout();
            return redirect()->route('blacklisted');
        }

        return $next($request);
    }
}