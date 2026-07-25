<?php
// app/Models/State.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'state_code',
        'state_name',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Relationship with users/registrations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Scope for active states
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get states by country
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }
}
