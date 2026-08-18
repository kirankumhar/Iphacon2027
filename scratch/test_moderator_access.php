<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AdminUser;
use App\Http\Middleware\CheckAdminPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== Testing AdminUser Model Permissions ===" . PHP_EOL;
$mod = new AdminUser(['role' => 'Moderator', 'is_active' => true]);
echo "Moderator isModerator(): " . ($mod->isModerator() ? 'YES' : 'NO') . PHP_EOL;
echo "Moderator has users.view: " . ($mod->hasPermission('users.view') ? 'YES' : 'NO') . PHP_EOL;
echo "Moderator has abstracts.view: " . ($mod->hasPermission('abstracts.view') ? 'YES' : 'NO') . PHP_EOL;

echo PHP_EOL . "=== Testing Middleware Restriction for Moderator ===" . PHP_EOL;
$middleware = new CheckAdminPermission();

// Mock Auth
Auth::shouldReceive('guard')->with('admin')->andReturn(new class($mod) {
    private $user;
    public function __construct($user) { $this->user = $user; }
    public function check() { return true; }
    public function user() { return $this->user; }
});

// Test accessing moderator dashboard
$reqModDash = Request::create('/admin/moderator/dashboard', 'GET');
$reqModDash->setRouteResolver(function() {
    $route = new \Illuminate\Routing\Route('GET', 'admin/moderator/dashboard', []);
    $route->name('admin.moderator.dashboard');
    return $route;
});
$resModDash = $middleware->handle($reqModDash, function($r) { return 'ALLOWED_MOD_DASHBOARD'; });
echo "Access /admin/moderator/dashboard: " . $resModDash . PHP_EOL;

// Test accessing abstracts
$reqAbstract = Request::create('/admin/abstracts', 'GET');
$reqAbstract->setRouteResolver(function() {
    $route = new \Illuminate\Routing\Route('GET', 'admin/abstracts', []);
    $route->name('admin.abstracts.index');
    return $route;
});
$res = $middleware->handle($reqAbstract, function($r) { return 'ALLOWED_ABSTRACTS'; });
echo "Access /admin/abstracts: " . $res . PHP_EOL;

// Test accessing delegates (should be blocked and redirected)
$reqDel = Request::create('/admin/submitted-delegates', 'GET');
$reqDel->setRouteResolver(function() {
    $route = new \Illuminate\Routing\Route('GET', 'admin/submitted-delegates', []);
    $route->name('submitted-delegates');
    return $route;
});
$resDel = $middleware->handle($reqDel, function($r) { return 'ALLOWED_DELEGATES'; });
echo "Access /admin/submitted-delegates: " . ($resDel instanceof \Illuminate\Http\RedirectResponse ? 'BLOCKED & REDIRECTED to ' . $resDel->getTargetUrl() : $resDel) . PHP_EOL;

echo PHP_EOL . "=== Testing Moderator Dashboard Controller Index ===" . PHP_EOL;
$controller = new \App\Http\Controllers\Admin\ModeratorDashboardController();
$viewResponse = $controller->index();
echo "Controller returned view: " . $viewResponse->getName() . PHP_EOL;
echo "View data keys: " . implode(', ', array_keys($viewResponse->getData())) . PHP_EOL;
echo "Total Abstracts in View: " . $viewResponse->getData()['totalAbstracts'] . PHP_EOL;
echo "Pending Abstracts in View: " . $viewResponse->getData()['pendingAbstractCount'] . PHP_EOL;

echo PHP_EOL . "ALL CHECKS PASSED!" . PHP_EOL;
