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
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->has('remember');

        // Find the admin user by username OR email
        $admin = AdminUser::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$admin) {
            Log::warning('Admin login failed: User not found', ['username' => $username]);
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        }

        // Debug: Check if account is active
        if (!$admin->is_active) {
            Log::warning('Admin login failed: Account inactive', ['username' => $username]);
            return back()->withErrors([
                'username' => 'Your account has been deactivated. Please contact the system administrator.'
            ])->withInput($request->except('password'));
        }

        // Debug: Verify password
        if (!Hash::check($password, $admin->password_hash)) {
            Log::warning('Admin login failed: Invalid password', ['username' => $username]);
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ])->withInput($request->except('password'));
        }

        // Manual login since Auth::attempt() is not working
        Auth::guard('admin')->login($admin, $remember);

        // Update last login
        $admin->update([
            'last_login' => now()
        ]);

        $request->session()->regenerate();

        Log::info('Admin logged in successfully', [
            'username' => $username,
            'role' => $admin->role
        ]);

        // Redirect based on role
        $redirectRoute = match($admin->role) {
            'Super Admin' => 'admin.dashboard',
            'Admin' => 'admin.dashboard',
            'Moderator' => 'admin.dashboard',
            default => 'admin.dashboard'
        };

        return redirect()->route($redirectRoute)->with('success', 'Welcome back, ' . $admin->full_name . '!');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }
}
