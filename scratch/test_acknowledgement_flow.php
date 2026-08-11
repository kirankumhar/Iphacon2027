<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;
use App\Models\User;

$user = User::first();

// 1. Create a draft registration
$reg = Registration::create([
    'user_id' => $user->id,
    'delegate_type' => 'Indian',
    'status' => 'Payment Submitted'
]);

// Generate Ack ID
$reg->acknowledgement_id = $reg->generateAcknowledgementId();
$reg->save();

echo "Step 1: Submitted Registration created." . PHP_EOL;
echo "Ack ID: " . $reg->acknowledgement_id . PHP_EOL;
echo "Reg Number (before approval): " . ($reg->registration_number ?? 'NULL') . PHP_EOL;

if (empty($reg->acknowledgement_id) || !preg_match('/^\d{8}$/', $reg->acknowledgement_id)) {
    echo "ERROR: Ack ID was not a valid 8-digit number! Got: {$reg->acknowledgement_id}" . PHP_EOL;
    exit(1);
}

if (!empty($reg->registration_number)) {
    echo "ERROR: Registration number should be NULL before approval!" . PHP_EOL;
    exit(1);
}

// 2. Approve Registration
$reg->registration_number = $reg->generateRegistrationNumber();
$reg->status = 'Approved';
$reg->approved_at = now();
$reg->save();

echo PHP_EOL . "Step 2: Registration Approved." . PHP_EOL;
echo "Ack ID: " . $reg->acknowledgement_id . PHP_EOL;
echo "Reg Number (after approval): " . $reg->registration_number . PHP_EOL;

if (empty($reg->registration_number)) {
    echo "ERROR: Registration number was not generated on approval!" . PHP_EOL;
    exit(1);
}

// Cleanup test record
$reg->delete();

echo PHP_EOL . "ALL ACKNOWLEDGEMENT ID & APPROVAL REG NUMBER TESTS PASSED 100%!" . PHP_EOL;
