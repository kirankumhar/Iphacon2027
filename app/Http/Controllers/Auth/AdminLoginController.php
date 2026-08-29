<?php

// app/Http/Controllers/Auth/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $throttleKey = Str::transliterate(Str::lower($request->input('username', '')).'|'.$request->ip());
        $maxAttempts = 5;
        $decaySeconds = 3600; // 1 hour lockout

        // Max 5 attempts allowed before lockout (1 hour)
        if (RateLimiter::tooManyAttempts($throttleKey, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $timeText = $seconds >= 60 ? (ceil($seconds / 60).' minute(s)') : ($seconds.' second(s)');

            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_LOCKED',
                "Too many admin login attempts for '{$request->input('username')}' from IP {$request->ip()}. Locked for {$seconds} seconds.",
                ['username' => $request->input('username'), 'ip' => $request->ip(), 'retry_after_seconds' => $seconds]
            );

            return back()->withErrors([
                'username' => "Too many login attempts. Your account has been locked. Please try again in {$timeText}.",
            ])->withInput($request->except('password'));
        }

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha',
        ], [
            'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            'username.required' => 'Username or Email is required.',
            'password.required' => 'Password is required.',
        ]);

        $username = trim($request->input('username'));
        $password = $request->input('password');
        $remember = $request->has('remember');

        // Find the admin user by username OR email
        $admin = AdminUser::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (! $admin) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            $remaining = RateLimiter::retriesLeft($throttleKey, $maxAttempts);

            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Account not found. Remaining attempts: {$remaining}.",
                ['username' => $username, 'reason' => 'Account not found', 'remaining_attempts' => $remaining]
            );

            $errorMessage = $remaining > 0
                ? "Invalid credentials. You have {$remaining} attempt(s) remaining before your account is locked for 1 hour (Max {$maxAttempts} attempts allowed)."
                : 'Too many failed login attempts. Your account has been locked for 1 hour.';

            return back()->withErrors([
                'username' => $errorMessage,
            ])->withInput($request->except('password'));
        }

        // Check if account is active
        if (! $admin->is_active) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Account deactivated.",
                ['username' => $username, 'reason' => 'Account deactivated'],
                $admin
            );

            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact the system administrator.',
            ])->withInput($request->except('password'));
        }

        // Verify password against stored password_hash
        if (! Hash::check($password, $admin->password_hash)) {
            RateLimiter::hit($throttleKey, $decaySeconds);
            $remaining = RateLimiter::retriesLeft($throttleKey, $maxAttempts);

            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Invalid password. Remaining attempts: {$remaining}.",
                ['username' => $username, 'reason' => 'Invalid password', 'remaining_attempts' => $remaining]
            );

            $errorMessage = $remaining > 0
                ? "Invalid credentials. You have {$remaining} attempt(s) remaining before your account is locked for 1 hour (Max {$maxAttempts} attempts allowed)."
                : 'Too many failed login attempts. Your account has been locked for 1 hour.';

            return back()->withErrors([
                'username' => $errorMessage,
            ])->withInput($request->except('password'));
        }

        // Clear throttle on successful login
        RateLimiter::clear($throttleKey);

        // Log the admin user in
        Auth::guard('admin')->login($admin, $remember);

        // Update last login timestamp
        $admin->update([
            'last_login' => now(),
        ]);

        // Record Activity Log
        \App\Models\ActivityLog::record(
            'ADMIN_LOGIN',
            'Admin '.($admin->full_name ?? $admin->username)." ({$admin->username}) logged in.",
            ['username' => $admin->username, 'role' => $admin->role],
            $admin
        );

        $request->session()->regenerate();

        Log::info('Admin logged in successfully', [
            'username' => $username,
            'role' => $admin->role,
        ]);

        $targetRoute = $admin->isModerator() ? route('admin.moderator.dashboard') : route('admin.dashboard');

        return redirect()->intended($targetRoute)
            ->with('success', 'Welcome back, '.($admin->full_name ?? $admin->username).'!');
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            \App\Models\ActivityLog::record(
                'ADMIN_LOGOUT',
                'Admin '.($admin->full_name ?? $admin->username)." ({$admin->username}) logged out.",
                ['username' => $admin->username, 'role' => $admin->role],
                $admin
            );
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
