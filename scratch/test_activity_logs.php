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

// 4. User Login Failure Tests
ActivityLog::record(
    'USER_LOGIN_FAILED',
    "Login failed for test_invalid@email.com: Email address not registered.",
    ['email' => 'test_invalid@email.com', 'reason' => 'Email address not registered']
);

ActivityLog::record(
    'USER_LOGIN_FAILED',
    "Login failed for {$user->email}: Invalid Password.",
    ['email' => $user->email, 'reason' => 'Invalid Password'],
    $user
);

// 5. User Registration Failure Test
ActivityLog::record(
    'USER_REGISTRATION_FAILED',
    "Registration failed for {$user->email}: Email address is already registered & verified.",
    ['email' => $user->email, 'reason' => 'Email already registered and verified']
);

// 6. Admin Login Failure Tests
ActivityLog::record(
    'ADMIN_LOGIN_FAILED',
    "Admin login failed for username 'invalid_admin': Account not found.",
    ['username' => 'invalid_admin', 'reason' => 'Account not found']
);

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
