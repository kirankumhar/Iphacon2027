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

        // Submitted delegates awaiting verification / moderation
        $submittedCount = Registration::where('status', 'Payment Submitted')
            ->where('is_deleted', '0')
            ->count();

        // Reverted applications needing follow-up
        $revertedCount = Registration::where('status', 'Reverted')
            ->where('is_deleted', '0')
            ->count();

        // Rejected applications
        $rejectedCount = Registration::where('status', 'Rejected')
            ->where('is_deleted', '0')
            ->count();

        // Approved Indian Delegates
        $approvedIndCount = Registration::where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'Indian')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'Indian');
                  })
                  ->orWhereNull('delegate_type');
            })
            ->where('is_deleted', '0')
            ->count();

        // Approved International Delegates
        $approvedIntCount = Registration::where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'International')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'International');
                  });
            })
            ->where('is_deleted', '0')
            ->count();

        // Total Abstract Submissions & Pending Abstracts
        $abstractCount = AbstractSubmission::count();
        $pendingAbstractCount = AbstractSubmission::whereIn('status', ['Pending', 'Under Review', 'Submitted'])
            ->orWhereNull('status')
            ->count();

        // CME Workshop Participants
        $cmeCount = Registration::where('participate_in_cme', 1)
            ->where('is_deleted', '0')
            ->count();

        // Recent Submitted Delegate Applications for Review
        $recentSubmittedRegistrations = Registration::with(['user', 'delegateCategory'])
            ->where('is_deleted', '0')
            ->whereIn('status', ['Payment Submitted', 'Pending', 'In Progress'])
            ->latest()
            ->take(6)
            ->get();

        // Recent Abstract Submissions
        $recentAbstracts = AbstractSubmission::with('user')
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'admin' => $admin,
            'submittedCount' => $submittedCount,
            'revertedCount' => $revertedCount,
            'rejectedCount' => $rejectedCount,
            'approvedIndCount' => $approvedIndCount,
            'approvedIntCount' => $approvedIntCount,
            'abstractCount' => $abstractCount,
            'pendingAbstractCount' => $pendingAbstractCount,
            'cmeCount' => $cmeCount,
            'recentSubmittedRegistrations' => $recentSubmittedRegistrations,
            'recentAbstracts' => $recentAbstracts,
        ];

        return view('admin.modules.dashboard.moderator-dashboard', $data);
    }
}
