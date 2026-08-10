<?php
// app/Http/Controllers/Auth/AdminLoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            'username.required' => 'Username or Email is required.',
            'password.required' => 'Password is required.'
        ]);

        $username = trim($request->input('username'));
        $password = $request->input('password');
        $remember = $request->has('remember');

        // Find the admin user by username OR email
        $admin = AdminUser::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$admin) {
            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Account not found.",
                ['username' => $username, 'reason' => 'Account not found']
            );
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        }

        // Check if account is active
        if (!$admin->is_active) {
            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Account deactivated.",
                ['username' => $username, 'reason' => 'Account deactivated'],
                $admin
            );
            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact the system administrator.'
            ])->withInput($request->except('password'));
        }

        // Verify password against stored password_hash
        if (!Hash::check($password, $admin->password_hash)) {
            \App\Models\ActivityLog::record(
                'ADMIN_LOGIN_FAILED',
                "Admin login failed for username '{$username}': Invalid password.",
                ['username' => $username, 'reason' => 'Invalid password'],
                $admin
            );
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        }

        // Log the admin user in
        Auth::guard('admin')->login($admin, $remember);

        // Update last login timestamp
        $admin->update([
            'last_login' => now()
        ]);

        // Record Activity Log
        \App\Models\ActivityLog::record(
            'ADMIN_LOGIN',
            "Admin " . ($admin->full_name ?? $admin->username) . " ({$admin->username}) logged in.",
            ['username' => $admin->username, 'role' => $admin->role],
            $admin
        );

        $request->session()->regenerate();

        Log::info('Admin logged in successfully', [
            'username' => $username,
            'role' => $admin->role
        ]);

        $targetRoute = $admin->isModerator() ? route('admin.moderator.dashboard') : route('admin.dashboard');

        return redirect()->intended($targetRoute)
            ->with('success', 'Welcome back, ' . ($admin->full_name ?? $admin->username) . '!');
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            \App\Models\ActivityLog::record(
                'ADMIN_LOGOUT',
                "Admin " . ($admin->full_name ?? $admin->username) . " ({$admin->username}) logged out.",
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
