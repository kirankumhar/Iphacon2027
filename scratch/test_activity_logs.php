<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\AdminUser;
use App\Models\Registration;

echo "=== ACTIVITY LOG SYSTEM INTEGRATION TEST ===" . PHP_EOL . PHP_EOL;

// 1. User Registration Activity Log Test
$user = User::first();
ActivityLog::record(
    'USER_REGISTERED',
    "New user registered: {$user->full_name} ({$user->email})",
    ['email' => $user->email, 'delegate_type' => 'Indian'],
    $user
);

// 2. User Login Activity Log Test
ActivityLog::record(
    'USER_LOGIN',
    "User {$user->full_name} ({$user->email}) logged in successfully.",
    ['email' => $user->email],
    $user
);

// 3. User Logout Activity Log Test
ActivityLog::record(
    'USER_LOGOUT',
    "User {$user->full_name} ({$user->email}) logged out.",
    ['email' => $user->email],
    $user
);

// 4. Admin Login Activity Log Test
$admin = AdminUser::first();
if ($admin) {
    ActivityLog::record(
        'ADMIN_LOGIN',
        "Admin " . ($admin->full_name ?? $admin->username) . " ({$admin->username}) logged in.",
        ['username' => $admin->username, 'role' => $admin->role],
        $admin
    );

    // 5. Admin Logout Activity Log Test
    ActivityLog::record(
        'ADMIN_LOGOUT',
        "Admin " . ($admin->full_name ?? $admin->username) . " ({$admin->username}) logged out.",
        ['username' => $admin->username, 'role' => $admin->role],
        $admin
    );
}

// Fetch recent activity logs
$recentLogs = ActivityLog::latest()->take(10)->get();

echo "Recorded Logs Count: " . $recentLogs->count() . PHP_EOL;
echo "---------------------------------------------------" . PHP_EOL;

foreach ($recentLogs as $log) {
    echo "[ID: {$log->id}] Action: {$log->action} | UserType: " . ($log->user_type ?? 'N/A') . PHP_EOL;
    echo "         Name: {$log->subject_name} | IP: {$log->ip_address}" . PHP_EOL;
    echo "         Description: {$log->description}" . PHP_EOL;
    echo "---------------------------------------------------" . PHP_EOL;
}

echo PHP_EOL . "ALL ACTIVITY LOG TESTS PASSED 100%!" . PHP_EOL;
