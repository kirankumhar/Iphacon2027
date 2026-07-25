<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\Country;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;

class RegisterController extends Controller
{
    // public function showRegistrationForm()
    // {
    //     $countries = Country::where('is_active', true)->get();
    //     return view('auth.register', compact('countries'));
    // }

    public function showRegistrationForm()
    {
        $countries = Country::active()->orderBy('country_name')->get();
        return view('conference-registration', compact('countries'));
    }

    public function register(RegisterRequest $request)
    {
        try {
            // Check if user exists with this email
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                if ($existingUser->hasVerifiedEmail()) {
                    // Email is verified, show error
                    return back()->withErrors([
                        'email' => 'This email address is already registered and verified. Please use a different email or try logging in.'
                    ])->withInput($request->except(['password', 'password_confirmation']));
                } else {
                    // Email exists but not verified, update the user
                    $userData = $this->prepareUserData($request);
                    $existingUser->update($userData);
                    $user = $existingUser;
                }
            } else {
                // Create new user
                $userData = $this->prepareUserData($request);
                $user = User::create($userData);
            }

            // Generate verification token and send email (gracefully handling mail server timeouts)
            try {
                $user->generateVerificationToken();
                $user->notify(new CustomVerifyEmail());
            } catch (\Exception $mailEx) {
                \Illuminate\Support\Facades\Log::warning('Verification email sending failed: ' . $mailEx->getMessage());
            }

            return redirect()->route('verification.notice')
                ->with('success', 'Registration successful! Please check your email for verification link.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->withErrors([
                'registration' => 'Registration failed: ' . $e->getMessage()
            ])->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    private function prepareUserData(RegisterRequest $request)
    {
        // Get country details safely
        $country = $request->country_id ? Country::find($request->country_id) : null;

        // Set default values based on delegate type
        $prefix = $request->prefix ?? 'Dr.';

        $country_id = $request->delegate_type === 'Indian' ? 1 : ($country ? $country->id : 1);
        $country_code = $request->delegate_type === 'Indian' ? '+91' : ($country ? $country->phone_code : '+91');

        $defaultName = ucfirst(explode('@', $request->email)[0]);

        return [
            'prefix' => $prefix,
            'full_name' => $request->full_name ?? $defaultName,
            'gender' => $request->gender ?? 'Male',
            'date_of_birth' => $request->date_of_birth ?? '1990-01-01',
            'country_id' => $country_id,
            'mobile_country_code' => $country_code,
            'mobile_number' => $request->mobile_number ?? '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'delegate_type' => $request->delegate_type,
            'consent' => true,
            'email_verified_at' => null,
            'verification_token' => null,
            'verification_sent_at' => null,
        ];
    }

    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['verification' => 'Invalid verification link.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Your email is already verified. Welcome back!');
        }

        if (hash_equals($request->route('hash'), sha1($user->getEmailForVerification()))) {
            $user->markEmailAsVerified();

            // Log the user in
            Auth::login($user);

            // Update last login
            $user->update([
                'last_login' => now(),
                'last_ip' => $request->ip()
            ]);

            event(new Registered($user));

            return redirect()->route('login')
                ->with('success', 'Email verified successfully! Login with your credentials.');
        }

        return redirect()->route('login')
            ->withErrors(['verification' => 'Invalid verification link.']);
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No user found with this email address.'
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'This email is already verified.'
            ]);
        }

        // Check if verification was sent recently (prevent spam)
        if ($user->verification_sent_at && $user->verification_sent_at->diffInMinutes(now()) < 2) {
            return back()->withErrors([
                'email' => 'Verification email was sent recently. Please 2 minutes wait before requesting another.'
            ]);
        }

        $user->generateVerificationToken();
        $user->notify(new CustomVerifyEmail());

        return back()->with('success', 'Verification email sent successfully!');
    }
}
