<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;

$registrations = Registration::all();
echo "Found " . $registrations->count() . " registrations." . PHP_EOL;

foreach ($registrations as $r) {
    if (empty($r->acknowledgement_id)) {
        $r->acknowledgement_id = $r->generateAcknowledgementId();
    }
    
    // If approved and no registration_number, generate XXXX-XXXX-XXXX
    if ($r->status === 'Approved' && empty($r->registration_number)) {
        $r->registration_number = $r->generateRegistrationNumber();
    }
    
    // If not approved, reset registration_number to null
    if ($r->status !== 'Approved' && !empty($r->registration_number)) {
        $r->registration_number = null;
    }
    
    $r->save();
    echo "ID: {$r->id} | Status: {$r->status} | Ack: {$r->acknowledgement_id} | RegNo: " . ($r->registration_number ?? 'NULL') . PHP_EOL;
}

echo "FINISHED SUCCESSFUL POPULATION!" . PHP_EOL;
