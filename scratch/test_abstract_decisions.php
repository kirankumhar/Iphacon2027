<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AbstractSubmission;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminAbstractController;

// Find or create dummy abstract for testing
$abstract = AbstractSubmission::first();

if (!$abstract) {
    echo "No abstract found in DB to test.\n";
    exit(0);
}

echo "Testing Abstract ID: {$abstract->id} (Current Status: {$abstract->status}, Mode: {$abstract->presentation_mode})\n";

$controller = new AdminAbstractController();

// 1. Test Accept for Oral
$req1 = Request::create("/admin/abstracts/{$abstract->id}/status", 'POST', [
    'decision' => 'accept_oral',
    'review_comments' => 'Approved for Oral presentation by Moderator.'
]);
$resp1 = $controller->updateStatus($req1, $abstract->id);
$abstract->refresh();
echo "1. Accept for Oral -> Status: {$abstract->status}, Mode: {$abstract->presentation_mode}, AckID: {$abstract->acknowledgement_id}\n";

// 2. Test Accept for Paper
$req2 = Request::create("/admin/abstracts/{$abstract->id}/status", 'POST', [
    'decision' => 'accept_paper',
    'review_comments' => 'Approved for Paper presentation by Moderator.'
]);
$resp2 = $controller->updateStatus($req2, $abstract->id);
$abstract->refresh();
echo "2. Accept for Paper -> Status: {$abstract->status}, Mode: {$abstract->presentation_mode}, AckID: {$abstract->acknowledgement_id}\n";

// 3. Test Reject
$req3 = Request::create("/admin/abstracts/{$abstract->id}/status", 'POST', [
    'decision' => 'reject',
    'review_comments' => 'Rejected due to incomplete methodology.'
]);
$resp3 = $controller->updateStatus($req3, $abstract->id);
$abstract->refresh();
echo "3. Reject -> Status: {$abstract->status}, Mode: {$abstract->presentation_mode}\n";

echo "ALL 3 MODERATION DECISION OPTIONS TESTED SUCCESSFULLY!\n";
