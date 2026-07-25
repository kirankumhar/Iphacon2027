<?php
// app/Models/Registration.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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


    // Helper methods
    public function calculateTotalAmount()
    {
        if ($this->delegate_type === 'International') {
            return 175.00; // Fixed amount for foreign delegates
        }

        $total = 0;

        // Add delegate category fee
        if ($this->delegateCategory) {
            $total += $this->delegateCategory->indian_fee;
        }

        // Add accompanying persons fee (4000 per person)
        $total += ($this->accompanying_persons ?? 0) * 4000;

        // Add CME fee if participating
        if ($this->participate_in_cme) {
            $total += 1000;
        }

        return $total;
    }

    public function updateStepAndCalculateTotal($step)
    {
        // $this->step_completed = max($this->step_completed, $step);
        $this->step_completed =  $step;
        $this->total_amount = $this->calculateTotalAmount();
        $this->save();
    }

    public function generateRegistrationNumber(): string
    {
        $year  = now()->format('y');   // 26
        $month = now()->format('m');   // 04
        $prefix = $year . $month;      // 2604

        $last = static::where('registration_number', 'like', $prefix . '%')
            ->orderBy('registration_number', 'desc')
            ->lockForUpdate()
            ->first();

        if ($last) {
            $lastSeq = (int) substr($last->registration_number, 4);
            $nextSeq = $lastSeq + 1;
        } else {
            $nextSeq = 1119;
        }

        return $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
    }
}
