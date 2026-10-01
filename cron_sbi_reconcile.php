<?php

/**
 * SBI ePay Order Reconciliation Cron Runner
 * 
 * Can be executed via CLI:
 *   php cron_sbi_reconcile.php
 * 
 * Or via cPanel Cron Job:
 *   /usr/local/bin/php /home/user/public_html/cron_sbi_reconcile.php >/dev/null 2>&1
 */

// If accessed via web browser, require a secret token to prevent unauthorized execution
if (php_sapi_name() !== 'cli') {
    $providedToken = $_GET['token'] ?? '';
    $configuredSecret = getenv('CRON_SECRET') ?: 'sbi_reconcile_iphacon_2027';
    if ($providedToken !== $configuredSecret) {
        http_response_code(403);
        die('Access Denied: Invalid or missing token.');
    }
}

if (!class_exists(\Illuminate\Support\Facades\Artisan::class) || !\Illuminate\Support\Facades\Facade::getFacadeApplication()) {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
}

use Illuminate\Support\Facades\Artisan;

echo "[" . date('Y-m-d H:i:s') . "] Running SBI ePay Order Reconciliation Cron...\n";

Artisan::call('sbi:reconcile-orders');

echo Artisan::output();

echo "[" . date('Y-m-d H:i:s') . "] Reconciliation process completed.\n";
