<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Blacklist;

class CheckBlacklist
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->blacklist) {
            auth()->logout();
            return redirect()->route('blacklisted');
        }

        return $next($request);
    }
}