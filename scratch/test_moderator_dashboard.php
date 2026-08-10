<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;

// Fetch moderator user
$moderator = AdminUser::where('role', 'Moderator')->first();

if (!$moderator) {
    echo "NO MODERATOR FOUND IN DATABASE!\n";
    exit(1);
}

echo "Found Moderator: " . $moderator->username . " (ID: " . $moderator->id . ")\n";

// Authenticate as moderator
Auth::guard('admin')->login($moderator);

echo "Logged in via Auth guard 'admin'. Is Moderator? " . ($moderator->isModerator() ? 'YES' : 'NO') . "\n";

// Execute Controller
$controller = new \App\Http\Controllers\Admin\ModeratorDashboardController();
$view = $controller->index();

echo "Rendered View Name: " . $view->name() . "\n";
echo "View Data Keys: " . implode(', ', array_keys($view->getData())) . "\n";

$html = $view->render();
echo "Rendered HTML Length: " . strlen($html) . " bytes\n";
if (str_contains($html, 'IPHACON 2027 Moderator Control Center')) {
    echo "SUCCESS: Moderator Hero Banner text found in rendered HTML!\n";
} else {
    echo "ERROR: Moderator Hero Banner text missing!\n";
}
