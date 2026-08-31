<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $throttleKey = Str::transliterate(Str::lower($request->input('email', '')).'|'.$request->ip());
        $maxAttempts = 5;
        $decaySeconds = 3600; // 1 hour lockout

        // Max 5 attempts allowed before lockout (1 hour)
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $timeText = $seconds >= 60 ? (ceil($seconds / 60).' minute(s)') : ($seconds.' second(s)');

            \App\Models\ActivityLog::record(
                'USER_LOGIN_LOCKED',
                "Too many login attempts for {$request->email} from IP {$request->ip()}. Locked for {$seconds} seconds.",
                ['email' => $request->email, 'ip' => $request->ip(), 'retry_after_seconds' => $seconds]
            );

            return back()->withErrors([
                'email' => "Too many login attempts. Your account has been temporarily locked. Please try again in {$timeText}.",
            ])->withInput($request->except('password'));
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Clear throttle on successful authentication
            RateLimiter::clear($throttleKey);

            // Check if email is verified
            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                \App\Models\ActivityLog::record(
                    'USER_LOGIN_FAILED',
                    "Login failed for {$request->email}: Email address is not verified.",
                    ['email' => $request->email, 'reason' => 'Email not verified'],
                    $user
                );

                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.',
                ]);
            }

            // Update last login
            $user->update([
                'last_login' => now(),
                'last_ip' => $request->ip(),
            ]);

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'USER_LOGIN',
                "User {$user->full_name} ({$user->email}) logged in successfully.",
                ['email' => $user->email],
                $user
            );

            $request->session()->regenerate();

            // Invalidate any previous/concurrent sessions on other devices for this user
            try {
                Auth::logoutOtherDevices($credentials['password']);
                if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                    \Illuminate\Support\Facades\DB::table('sessions')
                        ->where('user_id', $user->id)
                        ->where('id', '!=', $request->session()->getId())
                        ->delete();
                }
            } catch (\Throwable $e) {
                // Continue if session table not present
            }

            return redirect()->intended('dashboard')->with('success', 'Welcome back, '.$user->full_name.'!');
        }

        // Increment failed attempts count (3600-second / 1-hour decay)
        RateLimiter::hit($throttleKey, $decaySeconds);

        $remaining = RateLimiter::retriesLeft($throttleKey, $maxAttempts);

        // Determine specific failure reason
        $existingUser = User::where('email', $request->email)->first();
        $reason = $existingUser ? 'Invalid Password' : 'Email address not registered';

        \App\Models\ActivityLog::record(
            'USER_LOGIN_FAILED',
            "Login failed for {$request->email}: {$reason}. Remaining attempts: {$remaining}.",
            ['email' => $request->email, 'reason' => $reason, 'remaining_attempts' => $remaining]
        );

        $errorMessage = $remaining > 0
            ? "Invalid credentials. You have {$remaining} attempt(s) remaining before your account is temporarily locked for 1 hour (Max {$maxAttempts} attempts allowed)."
            : 'Too many failed login attempts. Your account has been temporarily locked for 1 hour.';

        return back()->withErrors([
            'email' => $errorMessage,
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            \App\Models\ActivityLog::record(
                'USER_LOGOUT',
                "User {$user->full_name} ({$user->email}) logged out.",
                ['email' => $user->email],
                $user
            );
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
