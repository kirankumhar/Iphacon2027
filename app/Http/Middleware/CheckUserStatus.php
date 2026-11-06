<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     * Ensure user account is active. If disabled, log out and redirect to login.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (method_exists($user, 'isDisabled') && $user->isDisabled()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your account has been disabled. Please contact the conference secretariat for assistance.'
                ]);
            }
        }

        return $next($request);
    }
}
