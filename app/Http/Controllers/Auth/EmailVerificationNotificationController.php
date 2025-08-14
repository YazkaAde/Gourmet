<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect sesuai role
            return match ($request->user()->role) {
                'admin' => redirect()->intended(route('admin.dashboard', absolute: false)),
                'cashier' => redirect()->intended(route('cashier.dashboard', absolute: false)),
                'customer' => redirect()->intended(route('dashboard', absolute: false)),
            };
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
