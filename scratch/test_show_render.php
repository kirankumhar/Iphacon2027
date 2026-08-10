<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AbstractSubmission;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;

$moderator = AdminUser::where('role', 'Moderator')->first();
Auth::guard('admin')->login($moderator);

$abstract = AbstractSubmission::first();
$view = view('admin.modules.abstracts.show', compact('abstract'));
$html = $view->render();

if (str_contains($html, 'background-color: #0288D1 !important; color: #FFFFFF !important;')) {
    echo "SUCCESS: High-contrast Paper badge styling verified!\n";
} else {
    echo "WARNING: Paper badge styling not found!\n";
}

if (str_contains($html, 'background-color: #DC2626 !important; color: #FFFFFF !important;')) {
    echo "SUCCESS: High-contrast Reject badge styling verified!\n";
} else {
    echo "WARNING: Reject badge styling not found!\n";
}
