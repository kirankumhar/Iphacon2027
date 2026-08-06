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

            // Generate OTP and send email
            try {
                $user->generateOtp();
                $user->notify(new CustomVerifyEmail());
            } catch (\Exception $mailEx) {
                \Illuminate\Support\Facades\Log::warning('Verification email sending failed: ' . $mailEx->getMessage());
            }

            return redirect()->route('verification.notice')
                ->with('success', 'Registration successful! A 6-digit OTP has been sent to your email address.')
                ->with('email', $user->email);

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

    public function showVerificationNotice(Request $request)
    {
        return view('auth.verify-email');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ], [
            'otp.required' => 'Please enter the 6-digit OTP sent to your email.',
            'otp.digits' => 'OTP must be exactly 6 digits.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No user account found with this email address.'], 404);
            }
            return back()->withErrors(['email' => 'No user account found with this email address.'])->withInput();
        }

        if ($user->hasVerifiedEmail()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Email is already verified.', 'redirect' => route('login')]);
            }
            return redirect()->route('login')
                ->with('success', 'Your email is already verified. Please login to continue.');
        }

        if ($user->isOtpValid($request->otp)) {
            $user->markEmailAsVerified();

            // Log the user in
            Auth::login($user);

            // Update last login
            $user->update([
                'last_login' => now(),
                'last_ip' => $request->ip()
            ]);

            event(new Registered($user));

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email verified successfully! Redirecting...',
                    'redirect' => route('login')
                ]);
            }

            return redirect()->route('login')
                ->with('success', 'Email verified successfully! You can now sign in to your account.');
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP code. Please try again.'], 422);
        }

        return back()->withErrors([
            'otp' => 'Invalid or expired OTP code. Please enter the correct OTP or request a new one.'
        ])->withInput();
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No user found with this email address.'
            ])->withInput();
        }

        if ($user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'This email is already verified.'
            ])->withInput();
        }

        // Generate new OTP and send notification
        try {
            $user->generateOtp();
            $user->notify(new CustomVerifyEmail());
        } catch (\Exception $mailEx) {
            \Illuminate\Support\Facades\Log::warning('Resend OTP email failed: ' . $mailEx->getMessage());
        }

        return back()->with('success', 'A new 6-digit OTP has been sent to your email address!')
            ->withInput(['email' => $request->email]);
    }
}
