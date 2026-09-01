<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Registration;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

echo "Testing Receipt PDF Generation...\n";

$reg = Registration::with([
    'user',
    'latestPayment',
    'delegateCategory',
    'country',
    'state',
    'abstractSubmission'
])->first();

if (!$reg) {
    $reg = new Registration();
    $reg->id = 1;
    $reg->acknowledgement_id = 'IPHA2027-1001';
    $reg->registration_number = 'IPHA-2027-0001';
    $reg->status = 'Approved';
    $reg->delegate_type = 'Indian';
    $reg->dietary_preference = 'Vegetarian';
    $reg->participate_in_cme = true;
    $reg->created_at = now();
}

$pdf = Pdf::loadView('pdfs.registration', [
    'registration' => $reg,
    'payment' => $reg->latestPayment ?? new Payment(['transaction_id' => 'TXN987654321', 'payment_status' => 'Success']),
    'applicationNumber' => $reg->registration_number ?? $reg->acknowledgement_id
]);

$output = $pdf->output();
echo "PDF successfully generated! Output size: " . strlen($output) . " bytes\n";
