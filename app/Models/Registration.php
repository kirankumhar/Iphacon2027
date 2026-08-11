<?php
// app/Models/Registration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'acknowledgement_id',
        'registration_number',
        'photo_path',
        'address',
        'country_id',
        'state_id',
        'other_state',
        'city',
        'pin_code',
        'whatsapp_country_code',
        'whatsapp_number',
        'dietary_preference',
        'id_proof_type',
        'id_proof_number',
        'id_proof_document_path',
        'delegate_type',
        'delegate_category_id',
        'delegate_fee',
        'gst_amount',
        'accompanying_persons',
        'accompanying_fee',
        'participate_in_cme',
        'cme_fee',
        'total_amount',
        'membership_no',
        'is_ismm_member',
        'ismm_membership_no',
        'is_isham_member',
        'isham_membership_no',
        'is_young_isam_member',
        'young_isam_membership_no',
        'status',
        'step_completed',
        'rejection_reason',
        'submitted_at',
        'approved_at',
        'registration_pdf_path',
        'is_deleted',
        'deleted_datetime',
        'revert_reason',
        'reverted_at',
        'rejected_at'
    ];

    protected $casts = [
        'delegate_fee' => 'decimal:2',
        'gst_amount' => 'decimal:2',
        'accompanying_fee' => 'decimal:2',
        'cme_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'accompanying_persons' => 'integer',
        'step_completed' => 'integer',
        'participate_in_cme' => 'boolean',
        'is_ismm_member' => 'boolean',
        'is_isham_member' => 'boolean',
        'is_young_isam_member' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Model boot handler
     */
    protected static function booted()
    {
        static::saving(function ($registration) {
            if ($registration->status === 'Approved') {
                if (empty($registration->registration_number)) {
                    $registration->registration_number = $registration->generateRegistrationNumber();
                }
            } else {
                $registration->registration_number = null;
            }
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function delegateCategory()
    {
        return $this->belongsTo(DelegateCategory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->orderByDesc('id');
    }

    public function cmeApplication()
    {
        return $this->hasOne(CmeApplication::class)->latestOfMany();
    }


    // Helper methods
    public function calculateTotalAmount()
    {
        if ($this->delegate_type === 'International') {
            return 175.00; // Fixed amount for foreign delegates
        }

        $categoryBase = $this->delegateCategory ? (float)$this->delegateCategory->indian_fee : 0.00;
        $accompanyingBase = ($this->accompanying_persons ?? 0) * 5000.00;
        $cmeBase = $this->participate_in_cme ? 2000.00 : 0.00;

        $subtotal = $categoryBase + $accompanyingBase + $cmeBase;
        return round($subtotal * 1.18, 2);
    }

    public function updateAmounts()
    {
        if ($this->delegate_type === 'International') {
            $categoryBase = 45000.00;
            $cmeBase = $this->participate_in_cme ? 2000.00 : 0.00;
            $accompanyingBase = ($this->accompanying_persons ?? 0) * 5000.00;

            $subtotalBase = $categoryBase + $cmeBase + $accompanyingBase;
            $gstAmount = round($subtotalBase * 0.18, 2);
            $totalAmount = round($subtotalBase + $gstAmount, 2);

            $this->delegate_fee = $categoryBase;
            $this->cme_fee = $cmeBase;
            $this->accompanying_fee = $accompanyingBase;
            $this->gst_amount = $gstAmount;
            $this->total_amount = $totalAmount;
            return;
        }

        $categoryBase = $this->delegateCategory ? (float)$this->delegateCategory->indian_fee : 0.00;
        $cmeBase = $this->participate_in_cme ? 2000.00 : 0.00;
        $accompanyingBase = ($this->accompanying_persons ?? 0) * 5000.00;

        $subtotalBase = $categoryBase + $cmeBase + $accompanyingBase;
        $gstAmount = round($subtotalBase * 0.18, 2);
        $totalAmount = round($subtotalBase + $gstAmount, 2);

        $this->delegate_fee = $categoryBase;
        $this->cme_fee = $cmeBase;
        $this->accompanying_fee = $accompanyingBase;
        $this->gst_amount = $gstAmount;
        $this->total_amount = $totalAmount;
    }

    public function updateStepAndCalculateTotal($step)
    {
        $this->step_completed = $step;
        $this->updateAmounts();
        $this->save();
    }

    public function generateAcknowledgementId(): string
    {
        do {
            $ack = (string) mt_rand(10000000, 99999999);
        } while (static::where('acknowledgement_id', $ack)->exists());

        return $ack;
    }

    public function generateRegistrationNumber(): string
    {
        do {
            $part1 = strtoupper(Str::random(4));
            $part2 = strtoupper(Str::random(4));
            $part3 = strtoupper(Str::random(4));
            $number = "{$part1}-{$part2}-{$part3}";
        } while (static::where('registration_number', $number)->exists());

        return $number;
    }

    public function getMaskedIdProofNumberAttribute(): string
    {
        if (empty($this->id_proof_number)) {
            return 'N/A';
        }
        $val = trim($this->id_proof_number);
        if (strlen($val) <= 4) {
            return 'XXXXX' . $val;
        }
        return 'XXXXX' . substr($val, -4);
    }
}
