<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbstractSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminAbstractController extends Controller
{
    /**
     * Display the abstracts list page.
     */
    public function index(Request $request)
    {
        $totalAbstracts = AbstractSubmission::count();
        $submittedCount = AbstractSubmission::where('status', 'Submitted')->count();
        $acceptedCount = AbstractSubmission::where('status', 'Accepted')->count();
        $rejectedCount = AbstractSubmission::where('status', 'Rejected')->count();
        $oralCount = AbstractSubmission::where('presentation_mode', 'Oral Presentation')->count();
        $posterCount = AbstractSubmission::where('presentation_mode', 'Poster Presentation')->count();

        $query = AbstractSubmission::with(['user', 'registration']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('presentation_mode')) {
            $query->where('presentation_mode', $request->presentation_mode);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('acknowledgement_id', 'like', "%{$search}%")
                  ->orWhere('presenting_author_name', 'like', "%{$search}%")
                  ->orWhere('abstract_title', 'like', "%{$search}%")
                  ->orWhere('conference_theme', 'like', "%{$search}%")
                  ->orWhere('presenting_author_email', 'like', "%{$search}%");
            });
        }

        $abstracts = $query->orderBy('id', 'desc')->paginate(20);

        return view('admin.modules.abstracts.index', compact(
            'totalAbstracts',
            'submittedCount',
            'acceptedCount',
            'rejectedCount',
            'oralCount',
            'posterCount',
            'abstracts'
        ));
    }

    /**
     * Datatables JSON loader for abstracts.
     */
    public function getAbstracts(Request $request)
    {
        $query = AbstractSubmission::with(['user', 'registration']);

        // Filter by Status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Mode if specified
        if ($request->filled('presentation_mode')) {
            $query->where('presentation_mode', $request->presentation_mode);
        }

        // Global Search
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('acknowledgement_id', 'like', "%{$search}%")
                  ->orWhere('presenting_author_name', 'like', "%{$search}%")
                  ->orWhere('abstract_title', 'like', "%{$search}%")
                  ->orWhere('conference_theme', 'like', "%{$search}%")
                  ->orWhere('presenting_author_email', 'like', "%{$search}%");
            });
        }

        $totalRecords = AbstractSubmission::count();
        $filteredRecords = (clone $query)->count();

        // Sorting
        if ($request->has('order')) {
            $colIndex = $request->input('order.0.column');
            $colName = $request->input("columns.{$colIndex}.name");
            $dir = $request->input('order.0.dir', 'desc');

            if (in_array($colName, ['acknowledgement_id', 'presenting_author_name', 'presentation_mode', 'status', 'created_at'])) {
                $query->orderBy($colName, $dir);
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $abstracts = $query->skip($request->input('start', 0))
            ->take($request->input('length', 25))
            ->get();

        return response()->json([
            'draw'            => intval($request->input('draw')),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $abstracts
        ]);
    }

    /**
     * Display abstract detail view.
     */
    public function show($id)
    {
        $abstract = AbstractSubmission::with(['user', 'registration'])
            ->where('id', $id)
            ->orWhere('acknowledgement_id', $id)
            ->firstOrFail();

        return view('admin.modules.abstracts.show', compact('abstract'));
    }

    /**
     * Update status and review comments based on moderator decision.
     */
    public function updateStatus(Request $request, $id)
    {
        $abstract = AbstractSubmission::findOrFail($id);

        $decision = $request->input('decision') ?? $request->input('status');

        $status = $abstract->status;
        $presentationMode = $abstract->presentation_mode;
        $message = "Abstract decision updated successfully.";

        if (in_array($decision, ['accept_oral', 'Accept for Oral', 'Accepted for Oral'])) {
            $status = 'Accepted';
            $presentationMode = 'Oral Presentation';
            $message = "Abstract {$abstract->acknowledgement_id} has been Accepted for Oral Presentation.";
        } elseif (in_array($decision, ['accept_paper', 'accept_poster', 'Accept for Paper', 'Accepted for Paper', 'Accept for Poster', 'Accepted for Poster'])) {
            $status = 'Accepted';
            $presentationMode = 'Poster Presentation';
            $message = "Abstract {$abstract->acknowledgement_id} has been Accepted for Paper / Poster Presentation.";
        } elseif (in_array($decision, ['reject', 'Reject', 'Rejected'])) {
            $status = 'Rejected';
            $message = "Abstract {$abstract->acknowledgement_id} has been Rejected.";
        } else {
            $validated = $request->validate([
                'status'          => 'required|string',
                'presentation_mode' => 'nullable|string',
                'review_comments' => 'nullable|string',
            ]);
            $status = $validated['status'];
            if (!empty($validated['presentation_mode'])) {
                $presentationMode = $validated['presentation_mode'];
            }
            $message = "Abstract {$abstract->acknowledgement_id} status updated to {$status}.";
        }

        // Regenerate acknowledgement ID prefix if mode changed and reg number exists
        $ackId = $abstract->acknowledgement_id;
        if ($presentationMode !== $abstract->presentation_mode && $abstract->registration) {
            $regNum = $abstract->registration->registration_number ?? null;
            if ($regNum) {
                $ackId = AbstractSubmission::generateAcknowledgementId($presentationMode, $regNum, $abstract->user_id, $abstract->id);
            }
        }

        $reviewer = optional(auth('admin')->user())->full_name ?? optional(auth('admin')->user())->username ?? 'Moderator';

        $abstract->update([
            'status'            => $status,
            'presentation_mode' => $presentationMode,
            'acknowledgement_id'=> $ackId,
            'review_comments'   => $request->input('review_comments') ?? $abstract->review_comments,
            'reviewed_at'       => now(),
            'reviewed_by'       => $reviewer,
        ]);

        // Send email notification to author about status update
        try {
            $recipientEmail = $abstract->presenting_author_email ?: optional($abstract->user)->email;
            if ($recipientEmail) {
                Mail::send('emails.abstract_status_updated', [
                    'abstract' => $abstract
                ], function ($message) use ($recipientEmail, $abstract, $status) {
                    $message->to($recipientEmail)
                        ->subject('IPHACON 2027 : Abstract Review Status - ' . $status . ' (' . $abstract->acknowledgement_id . ')')
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));
                });
            }
        } catch (\Exception $e) {
            Log::error('Failed to send abstract status update email: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', $message);
    }
}
