<?php

namespace App\Http\Controllers;

use App\Models\AbstractSubmission;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AbstractSubmissionController extends Controller
{
    /**
     * Display the abstract submission form.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user) {
            $user->load('country');
        }
        $registration = Registration::where('user_id', $user->id)
            ->with(['country', 'state', 'delegateCategory'])
            ->first();
        
        $abstract = AbstractSubmission::where('user_id', $user->id)->first();

        return view('delegate.abstract-submission', compact('user', 'registration', 'abstract'));
    }

    /**
     * Store or update an abstract submission (Submit or Save Draft).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isDraft = $request->input('action') === 'save_draft';

        $rules = [
            'presenting_author_name'        => 'required|string|max:255',
            'presenting_author_designation' => 'required|string|max:255',
            'presenting_author_department'  => 'required|string|max:255',
            'presenting_author_institution' => 'required|string|max:255',
            'presenting_author_city'        => 'required|string|max:100',
            'presenting_author_state'       => 'required|string|max:100',
            'presenting_author_country'     => 'required|string|max:100',
            'presenting_author_email'       => 'required|email|max:255',
            'presenting_author_mobile'      => 'required|string|max:50',
            'medical_council_reg_no'        => 'nullable|string|max:100',
            'presentation_mode'             => 'required|string|in:Oral Presentation,Poster Presentation,No Preference',
            'presenter_category'            => 'required|string',
            'other_category_text'           => 'nullable|string|max:255',
            'conference_theme'              => 'required|string',
            'abstract_title'                => $isDraft ? 'nullable|string|max:500' : 'required|string|max:500',
            'keywords'                      => $isDraft ? 'nullable|string|max:500' : 'required|string|max:500',
            'abstract_background'           => $isDraft ? 'nullable|string' : 'required|string',
            'abstract_objectives'           => $isDraft ? 'nullable|string' : 'required|string',
            'abstract_methodology'          => $isDraft ? 'nullable|string' : 'required|string',
            'abstract_results'              => $isDraft ? 'nullable|string' : 'required|string',
            'abstract_conclusion'           => $isDraft ? 'nullable|string' : 'required|string',
            'attachment_file'               => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];

        $validated = $request->validate($rules);

        // Process Co-authors array
        $coAuthors = [];
        if ($request->has('co_author_name') && is_array($request->co_author_name)) {
            foreach ($request->co_author_name as $idx => $name) {
                if (!empty(trim($name))) {
                    $coAuthors[] = [
                        'name'        => $name,
                        'designation' => $request->co_author_designation[$idx] ?? '',
                        'department'  => $request->co_author_department[$idx] ?? '',
                        'institution' => $request->co_author_institution[$idx] ?? '',
                        'email'       => $request->co_author_email[$idx] ?? '',
                    ];
                }
            }
        }

        // Calculate total word count of structured abstract
        $bodyText = implode(' ', [
            $request->input('abstract_background', ''),
            $request->input('abstract_objectives', ''),
            $request->input('abstract_methodology', ''),
            $request->input('abstract_results', ''),
            $request->input('abstract_conclusion', ''),
        ]);

        $words = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($bodyText))));
        $totalWords = count($words);

        if (!$isDraft && $totalWords > 300) {
            return response()->json([
                'success' => false,
                'message' => 'Structured abstract exceeds maximum limit of 300 words. Current word count: ' . $totalWords
            ], 422);
        }

        // Validate Title Word Count (Max 25 words)
        if (!$isDraft && !empty($request->abstract_title)) {
            $titleWords = array_filter(explode(' ', preg_replace('/\s+/', ' ', trim($request->abstract_title))));
            $titleWordCount = count($titleWords);
            if ($titleWordCount > 25) {
                return response()->json([
                    'success' => false,
                    'message' => 'Abstract title exceeds maximum limit of 25 words. Current title words: ' . $titleWordCount
                ], 422);
            }
        }

        // Validate Keywords Count (3 to 5 keywords separated by commas)
        if (!$isDraft) {
            $keywordsArr = array_filter(array_map('trim', explode(',', $request->input('keywords', ''))));
            $kwCount = count($keywordsArr);
            if ($kwCount < 3 || $kwCount > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide between 3 and 5 keywords separated by commas. Current count: ' . $kwCount
                ], 422);
            }
        }

        // Attachment Upload if provided
        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $attachmentPath = $request->file('attachment_file')->store('abstract_attachments', 'public');
        }

        $registration = Registration::where('user_id', $user->id)->first();

        $dataToSave = [
            'user_id'                       => $user->id,
            'registration_id'               => $registration?->id,
            'presenting_author_name'        => $request->presenting_author_name,
            'presenting_author_designation' => $request->presenting_author_designation,
            'presenting_author_department'  => $request->presenting_author_department,
            'presenting_author_institution' => $request->presenting_author_institution,
            'presenting_author_city'        => $request->presenting_author_city,
            'presenting_author_state'       => $request->presenting_author_state,
            'presenting_author_country'     => $request->presenting_author_country,
            'presenting_author_email'       => $request->presenting_author_email,
            'presenting_author_mobile'      => $request->presenting_author_mobile,
            'medical_council_reg_no'        => $request->medical_council_reg_no,
            'co_authors'                    => $coAuthors,
            'presentation_mode'             => $request->presentation_mode,
            'presenter_category'            => $request->presenter_category,
            'other_category_text'           => $request->other_category_text,
            'conference_theme'              => $request->conference_theme,
            'abstract_title'                => $request->abstract_title,
            'keywords'                      => $request->keywords,
            'abstract_background'           => $request->abstract_background,
            'abstract_objectives'           => $request->abstract_objectives,
            'abstract_methodology'          => $request->abstract_methodology,
            'abstract_results'              => $request->abstract_results,
            'abstract_conclusion'           => $request->abstract_conclusion,
            'total_word_count'              => $totalWords,
            'status'                        => $isDraft ? 'Draft' : 'Submitted',
            'submitted_at'                  => $isDraft ? null : now(),
        ];

        if ($attachmentPath) {
            $dataToSave['attachment_path'] = $attachmentPath;
        }

        $abstract = AbstractSubmission::where('user_id', $user->id)->first();

        $dataToSave['acknowledgement_id'] = AbstractSubmission::generateAcknowledgementId(
            $request->presentation_mode,
            $registration?->registration_number,
            $user->id,
            $abstract?->id
        );

        if ($abstract) {
            $abstract->update($dataToSave);
        } else {
            $abstract = AbstractSubmission::create($dataToSave);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'            => true,
                'message'            => $isDraft ? 'Abstract draft saved successfully.' : 'Abstract submitted successfully!',
                'acknowledgement_id' => $abstract->acknowledgement_id,
                'status'             => $abstract->status,
            ]);
        }

        return redirect()->back()->with('success', $isDraft ? 'Draft saved.' : 'Abstract submitted successfully!');
    }
}
