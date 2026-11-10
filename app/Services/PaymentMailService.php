<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\Payment;
use App\Models\CmeApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PaymentMailService
{
    /**
     * Send official payment confirmation email with PDF receipt attached.
     *
     * @param Registration $registration
     * @param Payment|null $payment
     * @param bool $forceRegeneratePdf
     * @return bool
     */
    public static function sendPaymentConfirmation(Registration $registration, ?Payment $payment = null, bool $forceRegeneratePdf = false): bool
    {
        try {
            $registration->loadMissing([
                'user',
                'delegateCategory',
                'country',
                'state',
                'cmeApplication',
                'payments',
                'conferencePayment',
                'cmePayment',
            ]);

            $recipientEmail = $registration->user?->email;
            if (!$recipientEmail) {
                Log::warning("[PaymentMailService] No email address found for Registration ID: {$registration->id}");
                return false;
            }

            // Resolve effective payment record if not provided
            if (!$payment) {
                $payment = $registration->conferencePayment 
                    ?: ($registration->payments()
                        ->where(function ($q) {
                            $q->whereIn('payment_status', ['Success', 'PAID', 'Approved', 'Completed', '0300', 'OTS0000'])
                              ->orWhere('admin_verified', true);
                        })
                        ->latest('id')
                        ->first() 
                    ?: $registration->latestPayment);
            }

            $docId = $registration->registration_number ?? ($registration->acknowledgement_id ?? 'IPHACON2027');
            $year  = now()->format('Y');
            $month = now()->format('m');
            $filename = "Delegate_Registration_{$docId}.pdf";
            $storageRelativePath = "registrations_receipt/{$year}/{$month}/{$filename}";

            $pdfContent = null;
            $localDiskPath = storage_path("app/public/{$storageRelativePath}");

            // Generate receipt PDF if missing on disk, force requested, or not generated yet
            $pdfExistsOnDisk = Storage::disk('public')->exists($storageRelativePath);
            if ($forceRegeneratePdf || !$pdfExistsOnDisk || empty($registration->registration_pdf_path)) {
                try {
                    $isBundled = $payment && (float)($payment->cme_fee ?? 0) > 0 && (float)($payment->delegate_category_fee ?? 0) > 0;
                    $receiptType = $isBundled ? 'consolidated' : 'conference';
                    $receiptTitle = $isBundled 
                        ? 'IPHACON 2027 CONSOLIDATED CONFERENCE & WORKSHOP RECEIPT' 
                        : 'IPHACON 2027 MAIN CONFERENCE REGISTRATION RECEIPT';

                    $pdf = Pdf::loadView('pdfs.registration', [
                        'registration'      => $registration,
                        'payment'           => $payment,
                        'receiptType'       => $receiptType,
                        'receiptTitle'      => $receiptTitle,
                        'applicationNumber' => $docId
                    ])->setPaper('a4', 'portrait')
                        ->setOption('margin-top', 10)
                        ->setOption('margin-bottom', 10)
                        ->setOption('margin-left', 10)
                        ->setOption('margin-right', 10);

                    $pdfContent = $pdf->output();

                    Storage::disk('public')->put($storageRelativePath, $pdfContent);

                    $registration->update([
                        'registration_pdf_path' => $storageRelativePath,
                    ]);
                } catch (\Throwable $pdfEx) {
                    Log::error("[PaymentMailService] PDF receipt generation failed: " . $pdfEx->getMessage(), [
                        'registration_id' => $registration->id,
                        'docId' => $docId,
                    ]);
                }
            }

            // Dispatch payment confirmation email
            $subject = "IPHACON 2027 : Payment Confirmation & Official Receipt ({$docId})";

            Mail::send('emails.payment_confirmation', [
                'registration'   => $registration,
                'payment'        => $payment,
                'user'           => $registration->user,
                'registrationID' => $registration->registration_number,
            ], function ($message) use ($recipientEmail, $subject, $docId, $storageRelativePath, $localDiskPath, $pdfContent) {
                $message->to($recipientEmail)
                    ->subject($subject)
                    ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                $pdfAttachmentName = "IPHACON_2027_Payment_Receipt_{$docId}.pdf";

                // Attach file from disk if present, else fallback to in-memory bytes
                if (file_exists($localDiskPath)) {
                    $message->attach($localDiskPath, [
                        'as'   => $pdfAttachmentName,
                        'mime' => 'application/pdf',
                    ]);
                } elseif (Storage::disk('public')->exists($storageRelativePath)) {
                    $message->attach(storage_path("app/public/{$storageRelativePath}"), [
                        'as'   => $pdfAttachmentName,
                        'mime' => 'application/pdf',
                    ]);
                } elseif ($pdfContent) {
                    $message->attachData($pdfContent, $pdfAttachmentName, [
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            Log::info("[PaymentMailService] Payment confirmation email successfully sent to {$recipientEmail} ({$docId})");
            PaymentLogService::info('PAYMENT_CONFIRMATION_EMAIL_SENT', "Payment confirmation email sent to {$recipientEmail}", [
                'registration_id'     => $registration->id,
                'registration_number' => $registration->registration_number,
                'email'               => $recipientEmail,
                'payment_id'          => $payment?->id,
                'amount'              => $payment?->total_amount,
                'transaction_id'      => $payment?->transaction_id,
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error("[PaymentMailService] Error sending payment confirmation email: " . $e->getMessage(), [
                'registration_id' => $registration->id,
                'trace'           => substr($e->getTraceAsString(), 0, 500),
            ]);
            PaymentLogService::error('PAYMENT_CONFIRMATION_EMAIL_FAILED', $e->getMessage(), [
                'registration_id' => $registration->id,
                'email'           => $registration->user?->email,
            ]);
            return false;
        }
    }

    /**
     * Send standalone CME Workshop payment confirmation email.
     *
     * @param Registration $registration
     * @param Payment $payment
     * @param CmeApplication|null $cmeApp
     * @return bool
     */
    public static function sendCmePaymentConfirmation(Registration $registration, Payment $payment, ?CmeApplication $cmeApp = null): bool
    {
        try {
            $registration->loadMissing(['user', 'delegateCategory']);
            $recipientEmail = $cmeApp?->user?->email ?? ($registration->user?->email ?? null);

            if (!$recipientEmail) {
                Log::warning("[PaymentMailService] No recipient email found for CME confirmation.");
                return false;
            }

            $userModel = $cmeApp?->user ?? $registration->user;
            $docId = $registration->registration_number ?? ($registration->acknowledgement_id ?? 'IPHACON2027');

            $year  = now()->format('Y');
            $month = now()->format('m');
            $filename = "CME_Workshop_Receipt_{$docId}.pdf";
            $cmeReceiptPath = "registrations_receipt/{$year}/{$month}/{$filename}";
            $localPath = storage_path("app/public/{$cmeReceiptPath}");

            $pdfBytes = null;
            try {
                $pdf = Pdf::loadView('pdfs.registration', [
                    'registration'      => $registration,
                    'payment'           => $payment,
                    'receiptType'       => 'cme',
                    'receiptTitle'      => 'IPHACON 2027 PRE-CONFERENCE WORKSHOP (CME) RECEIPT',
                    'applicationNumber' => $docId
                ])->setPaper('a4', 'portrait')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $pdfBytes = $pdf->output();
                Storage::disk('public')->put($cmeReceiptPath, $pdfBytes);

                if ($cmeApp) {
                    $cmeApp->update(['payment_receipt_path' => $cmeReceiptPath]);
                }
            } catch (\Throwable $pdfEx) {
                Log::error("[PaymentMailService] CME PDF Receipt generation failed: " . $pdfEx->getMessage());
            }

            $subject = "IPHACON 2027 : Pre-Conference Workshop Registration & Payment Confirmation ({$docId})";

            Mail::send('emails.cme_confirmation', [
                'cmeApp'       => $cmeApp,
                'registration' => $registration,
                'payment'      => $payment,
                'user'         => $userModel,
            ], function ($message) use ($recipientEmail, $subject, $docId, $localPath, $cmeReceiptPath, $pdfBytes) {
                $message->to($recipientEmail)
                    ->subject($subject)
                    ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                $pdfName = "Pre-Conference Workshop Receipt - {$docId}.pdf";
                if (file_exists($localPath)) {
                    $message->attach($localPath, [
                        'as'   => $pdfName,
                        'mime' => 'application/pdf',
                    ]);
                } elseif (Storage::disk('public')->exists($cmeReceiptPath)) {
                    $message->attach(storage_path("app/public/{$cmeReceiptPath}"), [
                        'as'   => $pdfName,
                        'mime' => 'application/pdf',
                    ]);
                } elseif ($pdfBytes) {
                    $message->attachData($pdfBytes, $pdfName, [
                        'mime' => 'application/pdf',
                    ]);
                }
            });

            Log::info("[PaymentMailService] CME Workshop Confirmation email sent to: {$recipientEmail}");
            PaymentLogService::info('CME_CONFIRMATION_EMAIL_SENT', "CME confirmation email sent to {$recipientEmail}", [
                'registration_id' => $registration->id,
                'payment_id'      => $payment->id,
                'amount'          => $payment->total_amount,
            ]);

            return true;

        } catch (\Throwable $e) {
            Log::error("[PaymentMailService] CME Confirmation Email failed: " . $e->getMessage());
            return false;
        }
    }
}
