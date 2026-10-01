<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Services\SbiPaymentService;
use Illuminate\Support\Facades\Log;

class SbiReconcileOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sbi:reconcile-orders 
                            {--order= : Specific order reference number to reconcile (e.g. SBI41T1788928581)}
                            {--all : Reconcile all pending orders regardless of age}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch status of pending SBI ePay orders via Order Search API and fulfill customer services based on orderStatus';

    /**
     * Execute the console command.
     */
    public function handle(SbiPaymentService $sbiService): int
    {
        $this->info('====================================================');
        $this->info('Starting SBI ePay Order Reconciliation (Order Search API)...');
        $this->info('====================================================');
        Log::info('[SBI ePay Cron] Started Order Reconciliation job.');

        $specificOrder = $this->option('order');
        $checkAll = $this->option('all');

        if ($specificOrder) {
            $pendingPayments = Payment::where('gateway_transaction_id', $specificOrder)->get();

            if ($pendingPayments->isEmpty()) {
                $this->error("No payment record found with gateway_transaction_id: {$specificOrder}");
                return 1;
            }
        } else {
            $query = Payment::where('payment_method', 'SBI_ePay')
                ->whereIn('payment_status', ['Pending', 'UNDER_VERIFICATION'])
                ->whereNotNull('gateway_transaction_id');

            if (!$checkAll) {
                // Ignore orders initiated in the last 5 minutes to allow real-time customer completion
                $query->where('created_at', '<=', now()->subMinutes(5));
            }

            $pendingPayments = $query->latest()->get();
        }

        if ($pendingPayments->isEmpty()) {
            $this->info('No pending SBI ePay orders found for reconciliation.');
            Log::info('[SBI ePay Cron] No pending orders to reconcile.');
            return 0;
        }

        $this->info("Found {$pendingPayments->count()} pending order(s) to verify with SBI...");

        $successCount = 0;
        $failedCount = 0;
        $stillPendingCount = 0;

        foreach ($pendingPayments as $payment) {
            $orderRef = $payment->gateway_transaction_id;
            $amount = (float) $payment->total_amount;

            $this->line("--> Checking Order [{$orderRef}] for ₹{$amount} (Payment ID: {$payment->id})...");

            try {
                $result = $sbiService->reconcilePayment($payment);

                $status = $result['status'] ?? 'UNKNOWN';
                $orderStatus = $result['orderStatus'] ?? 'N/A';
                $message = $result['message'] ?? '';

                $this->info("    Bank orderStatus: [{$orderStatus}] | Result: {$status} - {$message}");
                Log::info("[SBI ePay Cron] Reconciled {$orderRef}: status={$orderStatus}, result={$status}");

                if ($status === 'SUCCESS_FULFILLED') {
                    $successCount++;
                } elseif (in_array($status, ['MARKED_FAILED', 'MARKED_EXPIRED'])) {
                    $failedCount++;
                } else {
                    $stillPendingCount++;
                }

            } catch (\Throwable $e) {
                $this->error("    Error checking Order [{$orderRef}]: " . $e->getMessage());
                Log::error("[SBI ePay Cron] Error checking Order {$orderRef}: " . $e->getMessage());
                $failedCount++;
            }
        }

        $this->info('----------------------------------------------------');
        $this->info("Summary: {$successCount} Approved/Fulfilled | {$failedCount} Failed/Expired | {$stillPendingCount} Still Pending");
        $this->info('Reconciliation completed successfully.');
        Log::info("[SBI ePay Cron] Finished Order Reconciliation. Success: {$successCount}, Failed: {$failedCount}, Still Pending: {$stillPendingCount}");

        return 0;
    }
}
