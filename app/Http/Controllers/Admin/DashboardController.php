<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        $appliedCount = Registration::where('status', 'Payment Submitted')
            ->where('is_deleted', '0')
            ->count();

        $IndApprovedCount = Registration::where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'Indian')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'Indian');
                  })
                  ->orWhereNull('delegate_type');
            })
            ->where('is_deleted', '0')
            ->count();

        $IntApprovedCount = Registration::where('status', 'Approved')
            ->where(function ($q) {
                $q->where('delegate_type', 'International')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('delegate_type', 'International');
                  });
            })
            ->where('is_deleted', '0')
            ->count();

        $abstractCount = \App\Models\AbstractSubmission::count();
        $recentRegistrations = Registration::with(['user', 'delegateCategory'])
            ->where('is_deleted', '0')
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'admin' => $admin,
            'stats' => $this->getStatsBasedOnRole($admin),
            'appliedCount' => $appliedCount,
            'IndApprovedCount' => $IndApprovedCount,
            'IntApprovedCount' => $IntApprovedCount,
            'abstractCount' => $abstractCount,
            'recentRegistrations' => $recentRegistrations,
        ];

        return view('admin.modules.dashboard.dashboard', $data);
    }

    private function getStatsBasedOnRole($admin)
    {
        $stats = [];

        if ($admin->hasPermission('users.view')) {
            $stats['total_users'] = \App\Models\User::count();
            // $stats['active_users'] = \App\Models\User::where('is_active', true)->count();
        }

        if ($admin->hasPermission('content.view')) {
            $stats['total_content'] = 150; // Replace with actual query
            $stats['published_content'] = 120; // Replace with actual query
        }

        if ($admin->role === 'Super Admin') {
            $stats['total_admins'] = \App\Models\AdminUser::count();
            $stats['system_health'] = 'Good';
        }

        return $stats;
    }

    public function getChangePassword(Request $request)
    {
        return view('admin.layouts.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'newPassword' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // at least one uppercase
                'regex:/[a-z]/',      // at least one lowercase
                'regex:/[0-9]/',      // at least one number
                'regex:/[@$!%*#?&]/', // at least one special character
            ],
            'confirmPassword' => 'required|same:newPassword',
        ], [
            'newPassword.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'confirmPassword.same' => 'Passwords do not match.',
        ]);

        $user = Auth::guard('admin')->user();

        // dd($user);
        $user->password_hash = $request->newPassword;
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}
