<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbstractSubmission extends Model
{
    use HasFactory;

    protected $table = 'abstract_submissions';

    protected $fillable = [
        'user_id',
        'registration_id',
        'acknowledgement_id',
        'presenting_author_name',
        'presenting_author_designation',
        'presenting_author_department',
        'presenting_author_institution',
        'presenting_author_city',
        'presenting_author_state',
        'presenting_author_country',
        'presenting_author_email',
        'presenting_author_mobile',
        'medical_council_reg_no',
        'co_authors',
        'presentation_mode',
        'presenter_category',
        'other_category_text',
        'conference_theme',
        'abstract_title',
        'keywords',
        'abstract_background',
        'abstract_objectives',
        'abstract_methodology',
        'abstract_results',
        'abstract_conclusion',
        'total_word_count',
        'attachment_path',
        'status',
        'submitted_at',
        'review_comments',
    ];

    protected $casts = [
        'co_authors' => 'array',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the abstract submission.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the registration associated with the abstract.
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    /**
     * Helper to generate unique acknowledgement ID based on presentation mode & registration number.
     * e.g. Oral Presentation -> IPHA-OP-{registration_number}
     *      Poster Presentation -> IPHA-PP-{registration_number}
     */
    public static function generateAcknowledgementId($presentationMode = null, $registrationNumber = null, $userId = null, $ignoreId = null)
    {
        $prefix = 'IPHA-ABS-';
        if ($presentationMode === 'Oral Presentation') {
            $prefix = 'IPHA-OP-';
        } elseif ($presentationMode === 'Poster Presentation') {
            $prefix = 'IPHA-PP-';
        }

        $suffix = $registrationNumber ?: ($userId ? 'USR-' . $userId : rand(1000, 9999));
        $code = $prefix . $suffix;

        $baseCode = $code;
        $counter = 1;
        while (static::where('acknowledgement_id', $code)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $code = $baseCode . '-' . $counter;
            $counter++;
        }

        return $code;
    }
}
