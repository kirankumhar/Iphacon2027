<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminUser;

$superAdmin = AdminUser::where('role', 'Super Admin')->first();
$moderator = AdminUser::where('role', 'Moderator')->first();

echo "SuperAdmin isModerator: " . ($superAdmin->isModerator() ? 'YES' : 'NO') . "\n";
echo "Moderator isModerator: " . ($moderator->isModerator() ? 'YES' : 'NO') . "\n";

$targetSuper = $superAdmin->isModerator() ? route('admin.moderator.dashboard') : route('admin.dashboard');
$targetMod = $moderator->isModerator() ? route('admin.moderator.dashboard') : route('admin.dashboard');

echo "SuperAdmin Login Redirect: " . $targetSuper . "\n";
echo "Moderator Login Redirect: " . $targetMod . "\n";
