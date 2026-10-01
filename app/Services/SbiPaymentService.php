<?php

namespace App\Services;

use Sbiepay\SBIEPayClient;
use App\Models\Registration;
use App\Models\Payment;
use App\Models\CmeApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SbiPaymentService
{
    /**
     * Get configured SBIEPayClient instance
     */
    public function getClient(): SBIEPayClient
    {
        return new SBIEPayClient([
            'apiKey'        => config('services.sbiepay.api_key'),
            'apiSecret'     => config('services.sbiepay.api_secret'),
            'encryptionKey' => config('services.sbiepay.encryption_key'),
        ], config('services.sbiepay.env', 'SANDBOX'), 'JSON', false);
    }

    /**
     * Call SBI ePay Order Search API
     *
     * @param string $orderRefNumber
     * @param float $orderAmount
     * @return array
     */
    public function searchOrder(string $orderRefNumber, float $orderAmount): array
    {
        Log::info("[SBI ePay Search] Calling Order Search API for Order: {$orderRefNumber}, Amount: {$orderAmount}");

        try {
            $client = $this->getClient();

            $payload = [
                'orderRefNumber' => $orderRefNumber,
                'orderAmount'    => (float) $orderAmount,
            ];

            $rawResponse = $client->order->search($payload);

            if (is_string($rawResponse)) {
                $decoded = json_decode($rawResponse, true) ?: $rawResponse;
            } else {
                $decoded = $rawResponse;
            }

            Log::info("[SBI ePay Search] Raw Response for {$orderRefNumber}:", is_array($decoded) ? $decoded : ['raw' => $decoded]);

            // SBI returns array structure: [ 'status' => 1, 'data' => [ [ [ 'orderInfo' => ..., 'paymentInfo' => [...] ] ] ] ]
            $orderInfo = null;
            $paymentInfo = null;

            if (isset($decoded['data'][0][0]['orderInfo'])) {
                $orderInfo = $decoded['data'][0][0]['orderInfo'];
                $paymentInfo = $decoded['data'][0][0]['paymentInfo'][0] ?? ($decoded['data'][0][0]['paymentInfo'] ?? []);
            } elseif (isset($decoded['data'][0]['orderInfo'])) {
                $orderInfo = $decoded['data'][0]['orderInfo'];
                $paymentInfo = $decoded['data'][0]['paymentInfo'][0] ?? ($decoded['data'][0]['paymentInfo'] ?? []);
            } elseif (isset($decoded['orderInfo'])) {
                $orderInfo = $decoded['orderInfo'];
                $paymentInfo = $decoded['paymentInfo'][0] ?? ($decoded['paymentInfo'] ?? []);
            }

            if ($orderInfo) {
                $orderStatus = strtoupper(trim(
                    $orderInfo['orderStatus'] 
                    ?? $paymentInfo['transactionStatus'] 
                    ?? ''
                ));

                $sbiOrderRef = $orderInfo['sbiOrderRefNumber'] 
                    ?? $paymentInfo['atrnNumber'] 
                    ?? $paymentInfo['bankTxnNumber'] 
                    ?? null;

                $atrnNumber = $paymentInfo['atrnNumber'] ?? null;
                $bankTxnNumber = $paymentInfo['bankTxnNumber'] ?? null;

                return [
                    'success'           => true,
                    'status'            => $orderStatus,
                    'orderRefNumber'    => $orderRefNumber,
                    'sbiOrderRefNumber' => $sbiOrderRef,
                    'atrnNumber'        => $atrnNumber,
                    'bankTxnNumber'     => $bankTxnNumber,
                    'orderInfo'         => $orderInfo,
                    'paymentInfo'       => $paymentInfo,
                    'rawResponse'       => is_string($rawResponse) ? $rawResponse : json_encode($rawResponse),
                ];
            }

            // If error in response
            $errorMessage = $decoded['errors'][0]['errorMessage'] ?? 'Order details not found';
            return [
                'success'     => false,
                'status'      => 'NOT_FOUND',
                'message'     => $errorMessage,
                'rawResponse' => is_string($rawResponse) ? $rawResponse : json_encode($rawResponse),
            ];

        } catch (\Throwable $e) {
            Log::error("[SBI ePay Search] Error querying {$orderRefNumber}: " . $e->getMessage());
            return [
                'success' => false,
                'status'  => 'ERROR',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Reconcile a single pending payment using Order Search API
     *
     * @param Payment $payment
     * @return array
     */
    public function reconcilePayment(Payment $payment): array
    {
        $orderRef = $payment->gateway_transaction_id;
        $amount = (float) $payment->total_amount;

        if (empty($orderRef)) {
            return [
                'status'  => 'SKIPPED',
                'message' => 'Missing gateway_transaction_id',
            ];
        }

        $searchResult = $this->searchOrder($orderRef, $amount);

        if (!$searchResult['success']) {
            return [
                'status'  => 'FAILED_SEARCH',
                'message' => $searchResult['message'] ?? 'Unable to search order',
            ];
        }

        $orderStatus = $searchResult['status'];
        $sbiOrderRef = $searchResult['sbiOrderRefNumber'] ?? $payment->transaction_id ?? $orderRef;
        $rawGatewayResponse = $searchResult['rawResponse'];

        // Check if status represents SUCCESS
        $isSuccess = in_array($orderStatus, ['PAID', 'SUCCESS', 'APPROVED', 'OTS0000', '0300', 'COMPLETED']);

        // Check if status represents FAILURE
        $isFailed = in_array($orderStatus, ['FAIL', 'FAILED', 'CANCEL', 'CANCELLED', 'USER_CANCELLED', 'ABORT', 'ABORTED', 'DECLINED', 'REJECTED']);

        // Detect if this order is CME workshop or Delegate registration
        $isCme = false;
        $cmeAppId = null;
        if (preg_match('/^SBICME(\d+)T/', $orderRef, $matches)) {
            $isCme = true;
            $cmeAppId = (int) $matches[1];
        } elseif (isset($searchResult['orderInfo']['otherDetails'])) {
            $other = json_decode($searchResult['orderInfo']['otherDetails'], true);
            if (is_array($other) && (($other['payment_type'] ?? '') === 'CME' || !empty($other['cme_app_id']))) {
                $isCme = true;
                $cmeAppId = $other['cme_app_id'] ?? null;
            }
        }

        if ($isSuccess) {
            if ($isCme) {
                $this->fulfillCmePayment($payment, $cmeAppId, $sbiOrderRef, $rawGatewayResponse, $amount);
            } else {
                $this->fulfillDelegatePayment($payment, $sbiOrderRef, $rawGatewayResponse, $amount);
            }

            return [
                'status'  => 'SUCCESS_FULFILLED',
                'message' => "Order {$orderRef} marked SUCCESS and customer service activated.",
                'orderStatus' => $orderStatus,
            ];
        }

        if ($isFailed) {
            $payment->update([
                'payment_status'   => 'Failed',
                'gateway_response' => $rawGatewayResponse,
            ]);

            return [
                'status'  => 'MARKED_FAILED',
                'message' => "Order {$orderRef} marked Failed based on orderStatus: {$orderStatus}.",
                'orderStatus' => $orderStatus,
            ];
        }

        // Status is still CREATED / PENDING / INITIATED
        // If order is older than 2 hours, SBI session has expired
        if ($payment->created_at && $payment->created_at->lt(now()->subHours(2))) {
            $payment->update([
                'payment_status'   => 'EXPIRED',
                'gateway_response' => $rawGatewayResponse,
            ]);

            return [
                'status'  => 'MARKED_EXPIRED',
                'message' => "Order {$orderRef} pending beyond timeout window, marked EXPIRED.",
                'orderStatus' => $orderStatus,
            ];
        }

        return [
            'status'  => 'STILL_PENDING',
            'message' => "Order {$orderRef} is currently in status: {$orderStatus} at bank.",
            'orderStatus' => $orderStatus,
        ];
    }

    /**
     * Fulfill services for CME Workshop Payment
     */
    public function fulfillCmePayment(Payment $payment, ?int $cmeAppId, string $sbiOrderRef, string $rawGatewayResponse, float $amount): void
    {
        $orderRef = $payment->gateway_transaction_id;
        $cmeApp = $cmeAppId ? CmeApplication::find($cmeAppId) : null;
        if (!$cmeApp && $payment->registration_id) {
            $cmeApp = CmeApplication::where('registration_id', $payment->registration_id)->latest()->first();
        }

        if ($cmeApp) {
            $cmeApp->update([
                'status'         => 'Approved',
                'transaction_id' => $sbiOrderRef,
                'submitted_at'   => $cmeApp->submitted_at ?? now(),
                'approved_at'    => $cmeApp->approved_at ?? now(),
            ]);
        }

        $payment->update([
            'payment_status'         => 'Success',
            'transaction_id'         => $sbiOrderRef,
            'gateway_response'       => $rawGatewayResponse,
            'admin_verified'         => true,
            'payment_date'           => $payment->payment_date ?? now(),
            'total_amount'           => $amount > 0 ? $amount : ($payment->total_amount ?: 2360.00),
            'cme_fee'                => 2000.00,
            'gst_amount'             => 360.00,
            'delegate_category_fee'  => 0.00,
            'accompanying_persons_fee' => 0.00,
        ]);

        $delegate = Registration::find($payment->registration_id);
        if ($delegate) {
            $delegate->participate_in_cme = 1;
            $delegate->cme_fee = 2000.00;
            $delegate->updateAmounts();
            $delegate->save();
        }

        // Generate CME Workshop PDF Receipt & Send Confirmation Email
        $cmeReceiptPath = null;
        try {
            if ($delegate) {
                $delegate->loadMissing(['user', 'delegateCategory', 'country', 'state']);
                $pdf = PDF::loadView('pdfs.registration', [
                    'registration'      => $delegate,
                    'payment'           => $payment,
                    'receiptTitle'      => 'IPHACON 2027 Pre-Conference Workshop Receipt',
                    'applicationNumber' => $delegate->registration_number ?? ($delegate->acknowledgement_id ?? ($cmeApp?->id ?? 'IPHACON'))
                ])->setPaper('a4', 'portrait')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $year  = now()->format('Y');
                $month = now()->format('m');
                $docName = $delegate->registration_number ?? ($delegate->acknowledgement_id ?? ($cmeApp?->id ?? time()));
                $filename = "Workshop_Receipt_{$docName}.pdf";
                $cmeReceiptPath = "registrations_receipt/{$year}/{$month}/{$filename}";

                Storage::disk('public')->put($cmeReceiptPath, $pdf->output());

                if ($cmeApp) {
                    $cmeApp->update(['payment_receipt_path' => $cmeReceiptPath]);
                }
            }
        } catch (\Throwable $pdfEx) {
            Log::error("[SBI ePay Service] CME Workshop PDF Receipt generation failed: " . $pdfEx->getMessage());
        }

        // Send CME Workshop Confirmation Email with attached PDF
        try {
            $recipientEmail = $cmeApp?->user?->email ?? ($delegate?->user?->email ?? null);
            if ($recipientEmail && $delegate) {
                $userModel = $cmeApp?->user ?? $delegate->user;
                $docId = $delegate->registration_number ?? ($delegate->acknowledgement_id ?? 'IPHACON');

                Mail::send('emails.cme_confirmation', [
                    'cmeApp'       => $cmeApp,
                    'registration' => $delegate,
                    'payment'      => $payment,
                    'user'         => $userModel,
                ], function ($message) use ($recipientEmail, $docId, $cmeReceiptPath) {
                    $message->to($recipientEmail)
                        ->subject("IPHACON 2027 : Pre-Conference Workshop Registration & Payment Confirmation ({$docId})")
                        ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                    if ($cmeReceiptPath && Storage::disk('public')->exists($cmeReceiptPath)) {
                        $localPath = storage_path("app/public/{$cmeReceiptPath}");
                        $message->attach($localPath, [
                            'as' => "Pre-Conference Workshop Receipt - {$docId}.pdf",
                            'mime' => 'application/pdf'
                        ]);
                    }
                });

                Log::info("[SBI ePay Service] CME Workshop Confirmation email sent successfully to: {$recipientEmail}");
            }
        } catch (\Throwable $mailEx) {
            Log::error("[SBI ePay Service] CME Confirmation Email failed: " . $mailEx->getMessage());
        }
    }

    /**
     * Fulfill services for Delegate Registration Payment
     */
    public function fulfillDelegatePayment(Payment $payment, string $sbiOrderRef, string $rawGatewayResponse, float $amount): void
    {
        $delegate = Registration::with(['user', 'delegateCategory', 'country', 'state'])->find($payment->registration_id);
        if (!$delegate) {
            Log::error("[SBI ePay Service] Registration not found for payment ID: {$payment->id}");
            return;
        }

        $payAmount = $amount > 0 ? $amount : ($delegate->total_amount ?: $delegate->calculateTotalAmount());

        if ($delegate->delegate_type === 'International') {
            $delFee = $delegate->delegate_fee ?: $payAmount;
            $cmeFee = 0.00;
            $accFee = 0.00;
            $gstAmt = 0.00;
        } else {
            $delFee = $delegate->delegate_fee ?: ($delegate->delegateCategory ? (float)$delegate->delegateCategory->indian_fee : 0.00);
            $cmeFee = $delegate->cme_fee ?: ($delegate->participate_in_cme ? 2000.00 : 0.00);
            $accFee = $delegate->accompanying_fee ?: (($delegate->accompanying_persons ?? 0) * 5000.00);
            $subtotal = $delFee + $cmeFee + $accFee;
            $gstAmt = $delegate->gst_amount ?: round($subtotal * 0.18, 2);
        }

        $payment->update([
            'payment_status'           => 'Success',
            'transaction_id'           => $sbiOrderRef,
            'gateway_response'         => $rawGatewayResponse,
            'admin_verified'           => true,
            'payment_date'             => $payment->payment_date ?? now(),
            'delegate_category_fee'    => $delFee,
            'accompanying_persons_fee' => $accFee,
            'cme_fee'                  => $cmeFee,
            'gst_amount'               => $gstAmt,
            'total_amount'             => $payAmount,
        ]);

        $registrationNo = $delegate->registration_number ?: $delegate->generateRegistrationNumber();
        if (empty($delegate->acknowledgement_id)) {
            $delegate->acknowledgement_id = $delegate->generateAcknowledgementId();
        }

        $wasAlreadyApproved = ($delegate->status === 'Approved');

        $delegate->updateAmounts();
        $delegate->status = 'Approved';
        $delegate->submitted_at = $delegate->submitted_at ?? now();
        $delegate->registration_number = $registrationNo;
        $delegate->approved_at = $delegate->approved_at ?? now();
        $delegate->save();

        // Send emails and generate PDF receipt if not already done
        if (!$wasAlreadyApproved || empty($delegate->registration_pdf_path)) {
            try {
                $pdf = PDF::loadView('pdfs.registration', [
                    'registration'      => $delegate,
                    'payment'           => $payment,
                    'applicationNumber' => $delegate->registration_number ?? $delegate->acknowledgement_id
                ])->setPaper('a4', 'portrait')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $year  = now()->format('Y');
                $month = now()->format('m');
                $docName = $delegate->registration_number ?? $delegate->acknowledgement_id;
                $filename = "Delegate_Registration_{$docName}.pdf";
                $path = "registrations_receipt/{$year}/{$month}/{$filename}";

                Storage::disk('public')->put($path, $pdf->output());

                $delegate->update([
                    'registration_pdf_path' => $path,
                ]);

                if ($delegate->user && $delegate->user->email) {
                    Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                        $message->to($delegate->user->email)
                            ->subject('IPHACON 2027 : Delegate Registration Confirmation')
                            ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027'));

                        $localPath = storage_path("app/public/{$path}");
                        if (file_exists($localPath)) {
                            $message->attach($localPath, [
                                'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                                'mime' => 'application/pdf'
                            ]);
                        }
                    });

                    Log::info("[SBI ePay Service] Registration Confirmation email sent to: {$delegate->user->email}");
                }
            } catch (\Throwable $e) {
                Log::error("[SBI ePay Service] Receipt Generation/Email failed in SBI reconcile: " . $e->getMessage());
            }
        }
    }
}
