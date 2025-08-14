<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Redirect sesuai role
            return match ($request->user()->role) {
                'admin' => redirect()->intended(route('admin.dashboard', absolute: false)),
                'cashier' => redirect()->intended(route('cashier.dashboard', absolute: false)),
                'customer' => redirect()->intended(route('dashboard', absolute: false)),
            };
        }

        return view('auth.verify-email');
    }
}
