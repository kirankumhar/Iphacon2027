<?php
// app/Models/Country.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_code',
        'country_name',
        'phone_code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relationship with states
    public function states()
    {
        return $this->hasMany(State::class);
    }

    // Scope for active countries
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get formatted display name (Country Name - Code)
    public function getDisplayNameAttribute()
    {
        return $this->country_name . ' (' . $this->phone_code . ')';
    }

    // Get phone code with plus sign
    public function getFormattedPhoneCodeAttribute()
    {
        return $this->phone_code;
    }
}
