<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbstractSubmission;
use Illuminate\Http\Request;

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
     * Update status and review comments.
     */
    public function updateStatus(Request $request, $id)
    {
        $abstract = AbstractSubmission::findOrFail($id);

        $validated = $request->validate([
            'status'          => 'required|string|in:Draft,Submitted,Under Review,Accepted,Rejected,Reverted',
            'review_comments' => 'nullable|string',
        ]);

        $abstract->update([
            'status'          => $validated['status'],
            'review_comments' => $validated['review_comments'] ?? null,
        ]);

        return redirect()->back()->with('success', "Abstract {$abstract->acknowledgement_id} status updated to {$abstract->status}.");
    }
}
