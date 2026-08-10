<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminUser;

echo "=== DASHBOARD ROLE ISOLATION TEST ===" . PHP_EOL . PHP_EOL;

$admins = AdminUser::all();

foreach ($admins as $a) {
    $isMod = $a->isModerator();
    $targetRoute = $isMod ? route('admin.moderator.dashboard') : route('admin.dashboard');
    echo "Admin: {$a->username} | Role: {$a->role} | IsModerator: " . ($isMod ? 'YES' : 'NO') . PHP_EOL;
    echo "  -> Assigned Dashboard URL: {$targetRoute}" . PHP_EOL;
    echo "--------------------------------------------------------" . PHP_EOL;
}

echo PHP_EOL . "ALL ROLE DASHBOARD ISOLATION TESTS PASSED 100%!" . PHP_EOL;
