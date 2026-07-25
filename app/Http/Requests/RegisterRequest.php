<?php
// app/Http/Requests/RegisterRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'delegate_type' => 'required|in:Indian,International',
            'country_id' => 'required|exists:countries,id',
            'email' => 'required|email|max:255',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'captcha' => 'required|captcha',
        ];
    }

    public function messages()
    {
        return [
            'delegate_type.required' => 'Please select delegate type.',
            'delegate_type.in' => 'Invalid delegate type selected.',
            'country_id.required' => 'Please select your country.',
            'country_id.exists' => 'Selected country is invalid.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'captcha.required' => 'Please enter the CAPTCHA.',
            'captcha.captcha' => 'CAPTCHA verification failed.',
        ];
    }
}
