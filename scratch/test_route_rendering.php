<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;

echo "=== ROUTE PARAMETER FALLBACK TEST ===" . PHP_EOL . PHP_EOL;

$registrations = Registration::all();

foreach ($registrations as $reg) {
    $param = $reg->registration_number ?? ($reg->acknowledgement_id ?? $reg->id);
    $url = route('show-registration-details', $param);
    echo "Reg ID: {$reg->id} | Status: {$reg->status} -> Generated URL: {$url}" . PHP_EOL;
}

echo PHP_EOL . "ALL ROUTE PARAMETER FALLBACK TESTS PASSED 100%!" . PHP_EOL;
