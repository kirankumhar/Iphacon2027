<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PaymentLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PaymentLogController extends Controller
{
    /**
     * Display payment logs page
     */
    public function index(Request $request)
    {
        $files = PaymentLogService::getLogFiles();
        $selectedFile = $request->query('file');

        if (empty($selectedFile) && !empty($files)) {
            $selectedFile = $files[0]['filename'];
        }

        $search = $request->query('search');
        $level = $request->query('level');

        $entries = [];
        if (!empty($selectedFile)) {
            $entries = PaymentLogService::getLogEntries($selectedFile, $search, $level);
        }

        // Summary counts for the selected file
        $stats = [
            'total'    => count($entries),
            'info'     => 0,
            'warning'  => 0,
            'error'    => 0,
            'success'  => 0,
        ];

        foreach ($entries as $entry) {
            $lvl = strtolower($entry['level'] ?? '');
            if (isset($stats[$lvl])) {
                $stats[$lvl]++;
            }
            if (($entry['action'] ?? '') === 'PAYMENT_SUCCESS' || ($entry['action'] ?? '') === 'CME_PAYMENT_SUCCESS') {
                $stats['success']++;
            }
        }

        return view('admin.modules.payments.payment-logs', compact('files', 'selectedFile', 'entries', 'stats', 'search', 'level'));
    }

    /**
     * Download a log file
     */
    public function download($filename)
    {
        $dir = PaymentLogService::getLogDirectory();
        $cleanName = basename($filename);
        $path = $dir . DIRECTORY_SEPARATOR . $cleanName;

        if (!File::exists($path)) {
            return redirect()->back()->with('error', 'Log file not found.');
        }

        return response()->download($path, $cleanName, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
