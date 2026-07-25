<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Country;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $user->load('country');
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('country');
        $countries = Country::active()->orderBy('country_name')->get();
        return view('profile.edit', compact('user', 'countries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prefix' => 'required|in:Dr.,Mr.,Mrs.,Prof.',
            'full_name' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s]+$/',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date|before:-18 years',
            'mobile_country_code' => 'required|string|exists:countries,phone_code',
            'mobile_number' => [
                'required',
                'string',
                'min:7',
                'max:15',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->mobile_country_code === '+91' && !preg_match('/^[0-9]{10}$/', $value)) {
                        $fail('Indian mobile number must be exactly 10 digits.');
                    } elseif (!preg_match('/^[0-9]{7,15}$/', $value)) {
                        $fail('Mobile number must contain only digits and be 7-15 characters long.');
                    }
                }
            ],
        ]);

        // Get country ID from phone code
        $country = Country::where('phone_code', $request->mobile_country_code)->first();

        $user->update([
            'prefix' => $request->prefix,
            'full_name' => $request->full_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'country_id' => $country ? $country->id : $user->country_id,
            'mobile_country_code' => $request->mobile_country_code,
            'mobile_number' => $request->mobile_number,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }
}
