<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmeApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'registration_id',
        'cme_fee',
        'gst_amount',
        'total_amount',
        'transaction_id',
        'payment_receipt_path',
        'status',
        'rejection_reason',
        'submitted_at',
        'approved_at'
    ];

    protected $casts = [
        'cme_fee' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
