<?php
// app/Models/DelegateCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelegateCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
        'indian_fee',
        'foreign_fee',
        'is_active'
    ];

    protected $casts = [
        'indian_fee' => 'decimal:2',
        'foreign_fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationship with registrations
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get formatted display name with fees
    public function getDisplayNameAttribute()
    {
        return $this->category_name . ' (Indian: ₹' . number_format($this->indian_fee) . ', Foreign: $' . $this->foreign_fee . ')';
    }

    // Get fee based on delegate type
    public function getFeeByType($delegateType)
    {
        return $delegateType === 'Indian' ? $this->indian_fee : $this->foreign_fee;
    }
}
