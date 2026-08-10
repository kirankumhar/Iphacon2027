<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email address before logging in.'
                ]);
            }

            // Update last login
            $user->update([
                'last_login' => now(),
                'last_ip' => $request->ip()
            ]);

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'USER_LOGIN',
                "User {$user->full_name} ({$user->email}) logged in successfully.",
                ['email' => $user->email],
                $user
            );

            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', 'Welcome back, ' . $user->full_name . '!');
        }

        \Illuminate\Support\Facades\Log::warning("User login failed for email: {$request->email}", [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
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
