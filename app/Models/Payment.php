<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'delegate_category_fee',
        'accompanying_persons_fee',
        'cme_fee',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'transaction_id',
        'gateway_response',
        'gateway_transaction_id',
        'payment_receipt_path',
        'admin_verified',
        'payment_date',
    ];

    protected $casts = [
        'delegate_category_fee' => 'decimal:2',
        'accompanying_persons_fee' => 'decimal:2',
        'cme_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'admin_verified' => 'boolean',
        'payment_date' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
