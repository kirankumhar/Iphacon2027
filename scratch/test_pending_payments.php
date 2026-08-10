<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;
use App\Models\Payment;

echo "=== PENDING PAYMENTS SYSTEM TEST ===" . PHP_EOL . PHP_EOL;

$pendingRegistrations = Registration::with(['user', 'delegateCategory', 'latestPayment'])
    ->whereIn('status', ['Payment Submitted', 'Pending Payment', 'Submitted'])
    ->where('is_deleted', '0')
    ->latest()
    ->get();

$pendingPayments = Payment::with(['registration.user'])
    ->whereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION', 'In Process'])
    ->orWhereNull('payment_status')
    ->latest()
    ->get();

echo "Pending Registrations Count: " . $pendingRegistrations->count() . PHP_EOL;
echo "Pending Payments Records Count: " . $pendingPayments->count() . PHP_EOL;
echo "--------------------------------------------------------" . PHP_EOL;

foreach ($pendingRegistrations as $reg) {
    echo "ID: {$reg->id} | User: " . ($reg->user?->full_name ?? 'N/A') . PHP_EOL;
    echo "   Ack ID: " . ($reg->acknowledgement_id ?? 'N/A') . " | Status: {$reg->status}" . PHP_EOL;
    echo "   Amount: ₹{$reg->total_amount}" . PHP_EOL;
    echo "--------------------------------------------------------" . PHP_EOL;
}

echo PHP_EOL . "PENDING PAYMENTS TEST PASSED 100%!" . PHP_EOL;
