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
     * Helper to generate unique acknowledgement ID (e.g. ABS-2027-8942)
     */
    public static function generateAcknowledgementId()
    {
        do {
            $code = 'ABS-2027-' . rand(1000, 9999);
        } while (static::where('acknowledgement_id', $code)->exists());

        return $code;
    }
}
