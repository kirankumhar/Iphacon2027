<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && $admin->isModerator()) {
            return redirect()->route('admin.moderator.dashboard');
        }

        $appliedCount = Registration::where(function ($q) {
                $q->whereIn('status', ['Payment Submitted', 'Submitted', 'Pending Payment'])
                  ->orWhereHas('latestPayment');
            })
            ->whereNotIn('status', ['Approved', 'Rejected', 'Draft'])
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

        // Payment Analytics & Revenue Trends
        $successStatuses = ['Success', 'PAID', 'Approved', 'Completed', '0300', 'OTS0000'];

        $verifiedPayments = Payment::where(function ($q) use ($successStatuses) {
                $q->whereIn('payment_status', $successStatuses)
                  ->orWhere('admin_verified', true);
            })
            ->get()
            ->unique(function ($p) {
                $txn = trim($p->transaction_id ?: ($p->gateway_transaction_id ?: ''));
                return $txn !== '' ? $txn : 'payment_' . $p->id;
            });

        $totalRevenue = (float) $verifiedPayments->sum('total_amount');
        $totalPaidTxns = $verifiedPayments->count();

        $sbiPayments = $verifiedPayments->filter(function ($p) {
            return $p->payment_method === 'SBI_ePay' || (!empty($p->gateway_transaction_id) && $p->payment_method !== 'QR_Code');
        });
        $sbiRevenue = (float) $sbiPayments->sum('total_amount');
        $sbiCount = $sbiPayments->count();

        $offlinePayments = $verifiedPayments->filter(function ($p) {
            return $p->payment_method === 'QR_Code' || (!empty($p->payment_receipt_path) && $p->payment_method !== 'SBI_ePay');
        });
        $offlineRevenue = (float) $offlinePayments->sum('total_amount');
        $offlineCount = $offlinePayments->count();

        $avgTicket = $totalPaidTxns > 0 ? round($totalRevenue / $totalPaidTxns, 2) : 0;

        // Monthly Trend
        $monthlyRaw = Payment::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month_label")
            )
            ->where(function ($q) use ($successStatuses) {
                $q->whereIn('payment_status', $successStatuses)
                  ->orWhere('admin_verified', true);
            })
            ->groupBy('ym', 'month_label')
            ->orderBy('ym')
            ->get();

        $monthlyLabels = [];
        $monthlyAmounts = [];
        $monthlyCounts = [];
        foreach ($monthlyRaw as $m) {
            $mPayments = $verifiedPayments->filter(function ($p) use ($m) {
                return $p->created_at && $p->created_at->format('Y-m') === $m->ym;
            });
            $monthlyLabels[] = $m->month_label;
            $monthlyAmounts[] = round($mPayments->sum('total_amount'), 2);
            $monthlyCounts[] = $mPayments->count();
        }

        // Daily Trend (Last 30 Days)
        $dailyRaw = Payment::select(
                DB::raw("DATE(created_at) as pay_date"),
                DB::raw("DATE_FORMAT(created_at, '%d %b') as date_label")
            )
            ->where(function ($q) use ($successStatuses) {
                $q->whereIn('payment_status', $successStatuses)
                  ->orWhere('admin_verified', true);
            })
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('pay_date', 'date_label')
            ->orderBy('pay_date')
            ->get();

        $dailyLabels = [];
        $dailyAmounts = [];
        $dailyCounts = [];
        foreach ($dailyRaw as $d) {
            $dPayments = $verifiedPayments->filter(function ($p) use ($d) {
                return $p->created_at && $p->created_at->format('Y-m-d') === $d->pay_date;
            });
            $dailyLabels[] = $d->date_label;
            $dailyAmounts[] = round($dPayments->sum('total_amount'), 2);
            $dailyCounts[] = $dPayments->count();
        }

        // Payment Status Counts
        $statusCounts = [
            'paid' => $totalPaidTxns,
            'pending' => Payment::where(function($q) {
                $q->whereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION', 'In Process'])
                  ->orWhereNull('payment_status');
            })->count(),
            'failed' => Payment::whereIn('payment_status', ['Failed', 'EXPIRED', 'Cancelled'])->count(),
        ];

        $paymentStats = [
            'totalRevenue' => $totalRevenue,
            'totalPaidTxns' => $totalPaidTxns,
            'sbiRevenue' => $sbiRevenue,
            'sbiCount' => $sbiCount,
            'offlineRevenue' => $offlineRevenue,
            'offlineCount' => $offlineCount,
            'avgTicket' => $avgTicket,
            'monthlyLabels' => $monthlyLabels,
            'monthlyAmounts' => $monthlyAmounts,
            'monthlyCounts' => $monthlyCounts,
            'dailyLabels' => $dailyLabels,
            'dailyAmounts' => $dailyAmounts,
            'dailyCounts' => $dailyCounts,
            'statusCounts' => $statusCounts,
            'methodLabels' => ['SBI ePay Gateway', 'UPI / QR Code Transfer'],
            'methodSeries' => [$sbiRevenue, $offlineRevenue],
            'methodCounts' => [$sbiCount, $offlineCount],
        ];

        $data = [
            'admin' => $admin,
            'stats' => $this->getStatsBasedOnRole($admin),
            'appliedCount' => $appliedCount,
            'IndApprovedCount' => $IndApprovedCount,
            'IntApprovedCount' => $IntApprovedCount,
            'abstractCount' => $abstractCount,
            'recentRegistrations' => $recentRegistrations,
            'paymentStats' => $paymentStats,
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
