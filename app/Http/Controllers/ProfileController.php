<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\Country;
use App\Models\ActivityLog;

class ProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();
        $user->load([
            'country',
            'registrations' => function ($q) {
                $q->with(['country', 'state', 'delegateCategory', 'payments' => function ($p) {
                    $p->orderByDesc('id');
                }, 'cmeApplication'])->orderByDesc('id');
            },
            'abstractSubmissions' => function ($q) {
                $q->orderByDesc('id');
            }
        ]);

        $registration = $user->registrations->first();
        $abstractSubmissions = $user->abstractSubmissions;
        $latestAbstract = $abstractSubmissions->first();
        $countries = Country::active()->orderBy('country_name')->get();

        return view('profile.show', compact('user', 'registration', 'abstractSubmissions', 'latestAbstract', 'countries'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load(['country', 'registrations']);
        $countries = Country::active()->orderBy('country_name')->get();
        return view('profile.edit', compact('user', 'countries'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prefix' => 'required|in:Dr.,Mr.,Mrs.,Prof.',
            'full_name' => 'required|string|min:2|max:255|regex:/^[A-Za-z\s\.]+$/',
            'designation' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:1024',
        ], [
            'photo.image' => 'Profile photo must be a valid image file.',
            'photo.mimes' => 'Profile photo must be a file of type: JPG, JPEG, PNG, or WEBP.',
            'photo.max' => 'Profile photo size must not exceed 1MB (1024 KB).',
        ]);

        // Get country ID from phone code
        $country = Country::where('phone_code', $request->mobile_country_code)->first();

        $userData = [
            'prefix' => $request->prefix,
            'full_name' => $request->full_name,
            'designation' => $request->designation,
            'institution' => $request->institution,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'country_id' => $country ? $country->id : $user->country_id,
            'mobile_country_code' => $request->mobile_country_code,
            'mobile_number' => $request->mobile_number,
        ];

        // Handle Profile Photo Upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');

            // Delete existing photo file if stored
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $userData['photo_path'] = $photoPath;

            // Sync with all user registrations so badge/receipt/PDF use the updated photo
            if ($user->registrations()->exists()) {
                $user->registrations()->update(['photo_path' => $photoPath]);
            }
        } elseif ($request->boolean('remove_photo')) {
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $userData['photo_path'] = null;

            if ($user->registrations()->exists()) {
                $user->registrations()->update(['photo_path' => null]);
            }
        }

        $user->update($userData);

        // Sync designation and institution with registration if empty on registration
        if ($user->registrations()->exists()) {
            if (!empty($request->designation)) {
                $user->registrations()->where(function ($q) {
                    $q->whereNull('designation')->orWhere('designation', '');
                })->update(['designation' => $request->designation]);
            }
            if (!empty($request->institution)) {
                $user->registrations()->where(function ($q) {
                    $q->whereNull('institution')->orWhere('institution', '');
                })->update(['institution' => $request->institution]);
            }
        }

        ActivityLog::record(
            'DELEGATE_PROFILE_UPDATED',
            "Delegate {$user->full_name} ({$user->email}) updated their profile" . ($request->hasFile('photo') ? ' including profile image.' : '.'),
            [
                'user_id' => $user->id,
                'email' => $user->email,
                'photo_updated' => $request->hasFile('photo'),
                'photo_removed' => $request->boolean('remove_photo')
            ]
        );

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
                    ->numbers(),
                function ($attribute, $value, $fail) {
                    if (preg_match_all('/[a-zA-Z]/', $value) < 4) {
                        $fail('The password must contain at least 4 alphabetic letters (A-Z, a-z).');
                    }
                    if (!preg_match('/[!@#$]/', $value)) {
                        $fail('The password must contain at least one special symbol (!, @, #, or $).');
                    }
                    if (preg_match('/[^A-Za-z0-9!@#$]/', $value)) {
                        $fail('Other special characters are not allowed. Only (!, @, #, $) are permitted as symbols.');
                    }
                }
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

        try {
            Auth::logoutOtherDevices($request->password);
        } catch (\Throwable $e) {
            // Continue
        }

        return redirect()->route('profile.show')
            ->with('success', 'Password changed successfully!');
    }
}
