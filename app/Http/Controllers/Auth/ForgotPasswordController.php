<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'captcha' => 'required|captcha'
        ], [
            'captcha.captcha' => 'Invalid CAPTCHA. Please try again.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if ($user && $user->isDisabled()) {
            return back()->withErrors([
                'email' => 'Your account has been disabled. Please contact the conference secretariat for assistance.'
            ])->withInput($request->except('captcha'));
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
