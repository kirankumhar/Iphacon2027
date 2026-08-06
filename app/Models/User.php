<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'prefix',
        'full_name',
        'gender',
        'date_of_birth',
        'country_id',
        'mobile_country_code',
        'mobile_number',
        'delegate_type',
        'email',
        'password',
        'consent',
        'verification_token',
        'verification_sent_at',
        'otp',
        'otp_expires_at',
        'last_login',
        'last_ip'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_sent_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'date_of_birth' => 'date',
        'consent' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Generate OTP for email verification
    public function generateOtp()
    {
        $this->otp = (string) rand(100000, 999999);
        $this->otp_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->otp;
    }

    // Check if OTP is valid
    public function isOtpValid($inputOtp)
    {
        if (!$this->otp || !$this->otp_expires_at) {
            return false;
        }

        return (string) $this->otp === (string) trim($inputOtp) && $this->otp_expires_at->isFuture();
    }

    // Generate verification token
    public function generateVerificationToken()
    {
        $this->verification_token = Str::random(64);
        $this->verification_sent_at = now();
        $this->save();

        return $this->verification_token;
    }

    // Check if verification token is valid (24 hours)
    public function isVerificationTokenValid()
    {
        if (!$this->verification_token || !$this->verification_sent_at) {
            return false;
        }

        return $this->verification_sent_at->diffInHours(now()) <= 24;
    }

    // Mark email as verified
    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => now(),
            'verification_token' => null,
            'verification_sent_at' => null,
            'otp' => null,
            'otp_expires_at' => null,
        ])->save();
    }

    // Check if email is verified
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    // Get the email address that should be used for verification.
    public function getEmailForVerification()
    {
        return $this->email;
    }
}
