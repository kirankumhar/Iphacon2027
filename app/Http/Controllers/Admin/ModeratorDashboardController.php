<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\AbstractSubmission;
use Illuminate\Support\Facades\Auth;

class ModeratorDashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && !$admin->isModerator()) {
            return redirect()->route('admin.dashboard');
        }

        // Abstract Metrics
        $totalAbstracts = AbstractSubmission::count();
        $pendingAbstractCount = AbstractSubmission::whereIn('status', ['Pending', 'Under Review', 'Submitted'])
            ->orWhereNull('status')
            ->count();
        
        $acceptedOralCount = AbstractSubmission::where('status', 'Accepted')
            ->where('presentation_mode', 'Oral Presentation')
            ->count();

        $acceptedPosterCount = AbstractSubmission::where('status', 'Accepted')
            ->where(function ($q) {
                $q->where('presentation_mode', 'Poster Presentation')
                  ->orWhere('presentation_mode', 'Paper Presentation')
                  ->orWhere('presentation_mode', 'like', '%Poster%')
                  ->orWhere('presentation_mode', 'like', '%Paper%');
            })
            ->count();

        $totalAcceptedCount = AbstractSubmission::where('status', 'Accepted')->count();
        $rejectedAbstractCount = AbstractSubmission::where('status', 'Rejected')->count();

        // Recent Abstract Submissions with User and Registration relationship
        $recentAbstracts = AbstractSubmission::with(['user', 'registration'])
            ->latest()
            ->take(10)
            ->get();

        $data = [
            'admin' => $admin,
            'totalAbstracts' => $totalAbstracts,
            'pendingAbstractCount' => $pendingAbstractCount,
            'acceptedOralCount' => $acceptedOralCount,
            'acceptedPosterCount' => $acceptedPosterCount,
            'totalAcceptedCount' => $totalAcceptedCount,
            'rejectedAbstractCount' => $rejectedAbstractCount,
            'recentAbstracts' => $recentAbstracts,
        ];

        return view('admin.modules.dashboard.moderator-dashboard', $data);
    }
}
