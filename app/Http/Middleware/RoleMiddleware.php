<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // RoleMiddleware.php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    $userRole = strtolower($request->user()->role);
    $allowedRoles = array_map('strtolower', $roles);
    
    if (!in_array($userRole, $allowedRoles)) {
        Log::error('Unauthorized access attempt', [
            'user_id' => $request->user()->id,
            'user_role' => $request->user()->role,
            'required_roles' => $roles
        ]);
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}
