<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\Payment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Sbiepay\SBIEPayClient;

class PaymentController extends Controller
{

    public function gateway($encRegistrationId = null)
    {
        $user = Auth::user();

        $registrationId = null;
        if ($encRegistrationId) {
            if (is_numeric($encRegistrationId)) {
                $registrationId = (int) $encRegistrationId;
            } else {
                try {
                    $decrypted = Crypt::decryptString($encRegistrationId);
                    $stepData = json_decode($decrypted, true);
                    if (isset($stepData['reg_id'])) {
                        $registrationId = (int) $stepData['reg_id'];
                    }
                } catch (\Exception $e) {
                    $registrationId = (int) $encRegistrationId;
                }
            }
        }

        if ($registrationId) {
            $registration = Registration::where('user_id', $user->id)
                ->where('id', $registrationId)
                ->with(['delegateCategory', 'country', 'state'])
                ->firstOrFail();
        } else {
            $registration = Registration::where('user_id', $user->id)
                ->whereIn('status', ['Draft', 'Pending Payment'])
                ->with(['delegateCategory', 'country', 'state'])
                ->latest()
                ->firstOrFail();
        }

        // Check if registration is ready for payment
        if ($registration->status !== 'Draft' && $registration->status !== 'Pending Payment' && $registration->step_completed < 3) {
            return redirect()->route('registration.create')
                ->with('error', 'Please complete all registration steps before payment.');
        }

        return view('payment.gateway', compact('registration'));
    }

    /**
     * Get configured SBIEPayClient instance
     */
    protected function getSbiClient(): SBIEPayClient
    {
        return new SBIEPayClient([
            'apiKey'        => config('services.sbiepay.api_key'),
            'apiSecret'     => config('services.sbiepay.api_secret'),
            'encryptionKey' => config('services.sbiepay.encryption_key'),
        ], config('services.sbiepay.env', 'SANDBOX'), 'JSON', true);
    }

    /**
     * Get SBI return URL dynamically based on active request
     */
    protected function getSbiReturnUrl(): string
    {
        $configured = config('services.sbiepay.return_url');
        if (empty($configured) || $configured === 'http://localhost/payment/sbi/response') {
            return url('/payment/sbi/response');
        }
        return $configured;
    }

    /**
     * Robust decrypt for SBI ePay callback payload
     */
    protected function decryptSbiPayload($rawPayload)
    {
        if (empty($rawPayload)) {
            return null;
        }

        $encKey = config('services.sbiepay.encryption_key');
        $clean = str_replace(' ', '+', trim($rawPayload));

        // Attempt 1: Double Base64 decode (Standard SBI encryptedPaymentFinalResponse format)
        try {
            $firstDecode = base64_decode($clean);
            if ($firstDecode) {
                $decrypted = \Sbiepay\utils\crypto\Aes::decrypt($encKey, $firstDecode);
                if ($decrypted) {
                    return json_decode($decrypted, true) ?: $decrypted;
                }
            }
        } catch (\Throwable $e) {
            \Log::debug('SBI Decrypt Attempt 1 error: ' . $e->getMessage());
        }

        // Attempt 2: Direct Aes decrypt (single Base64)
        try {
            $decrypted = \Sbiepay\utils\crypto\Aes::decrypt($encKey, $clean);
            if ($decrypted) {
                return json_decode($decrypted, true) ?: $decrypted;
            }
        } catch (\Throwable $e) {
            \Log::debug('SBI Decrypt Attempt 2 error: ' . $e->getMessage());
        }

        // Attempt 3: SDK decodeCallback
        try {
            $sbiClient = $this->getSbiClient();
            $decrypted = $sbiClient->crypto->decodeCallback($clean);
            if ($decrypted) {
                return is_string($decrypted) ? (json_decode($decrypted, true) ?: $decrypted) : $decrypted;
            }
        } catch (\Throwable $e) {
            \Log::debug('SBI Decrypt Attempt 3 error: ' . $e->getMessage());
        }

        // Attempt 4: SDK decrypt
        try {
            $sbiClient = $this->getSbiClient();
            $decrypted = $sbiClient->crypto->decrypt($clean);
            if ($decrypted) {
                return is_string($decrypted) ? (json_decode($decrypted, true) ?: $decrypted) : $decrypted;
            }
        } catch (\Throwable $e) {
            \Log::debug('SBI Decrypt Attempt 4 error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Initiate Online Payment with SBI ePay
     */
    public function initiateSbiPayment($encRegistrationId)
    {
        $user = Auth::user();

        \Log::info('--- [SBI ePay] Initiate Payment Requested ---', [
            'raw_param' => $encRegistrationId,
            'user_id'   => $user?->id,
            'user_email'=> $user?->email,
        ]);

        $registrationId = null;
        if (is_numeric($encRegistrationId)) {
            $registrationId = (int) $encRegistrationId;
        } else {
            try {
                $decrypted = Crypt::decryptString($encRegistrationId);
                $stepData = json_decode($decrypted, true);
                if (isset($stepData['reg_id'])) {
                    $registrationId = (int) $stepData['reg_id'];
                }
            } catch (\Exception $e) {
                $registrationId = (int) $encRegistrationId;
            }
        }

        \Log::info('[SBI ePay] Resolved Registration ID: ' . $registrationId);

        $registration = Registration::where('user_id', $user->id)
            ->where('id', $registrationId)
            ->with(['delegateCategory', 'country', 'state'])
            ->firstOrFail();

        if ($registration->status === 'Approved') {
            \Log::warning('[SBI ePay] Registration already approved for ID: ' . $registration->id);
            return redirect()->route('payment.success', ['registration' => $registration->registration_number ?? $registration->id])
                ->with('success', 'Registration is already approved.');
        }

        $totalAmount = (float) ($registration->total_amount ?: $registration->calculateTotalAmount());
        if ($totalAmount <= 0) {
            \Log::error('[SBI ePay] Invalid payable amount: ' . $totalAmount);
            return redirect()->back()->with('error', 'Invalid payable amount.');
        }

        try {
            $sbiClient = $this->getSbiClient();

            // Unique Alphanumeric Order Reference
            $orderRef = 'SBI' . $registration->id . 'T' . time();

            $orderDetails = [
                'mId'            => config('services.sbiepay.mid', '1000003'),
                'currencyCode'   => 'INR',
                'orderAmount'    => $totalAmount,
                'orderRefNumber' => $orderRef,
                'otherDetails'   => json_encode([
                    'registration_id' => $registration->id,
                    'user_id'         => $user->id,
                ]),
                'returnUrl'      => $this->getSbiReturnUrl(),
            ];

            \Log::info('[SBI ePay] Creating Order with Payload:', $orderDetails);

            $response = $sbiClient->order->create($orderDetails);

            if (is_string($response)) {
                $response = json_decode($response, true);
            }

            \Log::info('[SBI ePay] Order Create Raw Response:', (array) $response);

            if (isset($response['status']) && $response['status'] == 1 && !empty($response['data'][0]['transactionUrl'])) {
                $transactionUrl = $response['data'][0]['transactionUrl'];
                $sbiOrderRef = $response['data'][0]['sbiOrderRefNumber'] ?? 'N/A';
                $orderHash = $response['data'][0]['orderHash'] ?? 'N/A';

                \Log::info('[SBI ePay] Order Created Successfully!', [
                    'orderRefNumber'    => $orderRef,
                    'sbiOrderRefNumber' => $sbiOrderRef,
                    'orderHash'         => $orderHash,
                    'transactionUrl'    => $transactionUrl,
                ]);

                // Create initial pending payment record for reconciliation/tracking
                Payment::updateOrCreate(
                    [
                        'registration_id' => $registration->id,
                        'gateway_transaction_id' => $orderRef,
                    ],
                    [
                        'total_amount' => $totalAmount,
                        'currency' => 'INR',
                        'payment_method' => 'SBI_ePay',
                        'payment_status' => 'Pending',
                        'transaction_id' => $sbiOrderRef !== 'N/A' ? $sbiOrderRef : null,
                        'gateway_transaction_id' => $orderRef,
                        'admin_verified' => false,
                        'payment_date' => now(),
                    ]
                );

                return view('payment.sbi_redirect', compact('transactionUrl'));
            }

            \Log::error('[SBI ePay] Order Creation Failed. Response: ' . json_encode($response));
            $errMsg = $response['errors'][0]['errorMessage'] ?? 'Failed to initiate SBI payment.';
            return redirect()->back()->with('error', 'Payment Gateway Error: ' . $errMsg);

        } catch (\Throwable $e) {
            \Log::error('[SBI ePay] Exception during initiate payment:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Error initiating SBI payment: ' . $e->getMessage());
        }
    }

    /**
     * Initiate Online Payment for CME Workshop with SBI ePay
     */
    public function initiateCmeSbiPayment($encCmeAppId)
    {
        $user = Auth::user();

        \Log::info('--- [SBI ePay CME] Initiate CME Payment Requested ---', [
            'raw_param' => $encCmeAppId,
            'user_id'   => $user?->id,
            'user_email'=> $user?->email,
        ]);

        $cmeAppId = null;
        if (is_numeric($encCmeAppId)) {
            $cmeAppId = (int) $encCmeAppId;
        } else {
            try {
                $decrypted = Crypt::decryptString($encCmeAppId);
                $stepData = json_decode($decrypted, true);
                if (isset($stepData['cme_app_id'])) {
                    $cmeAppId = (int) $stepData['cme_app_id'];
                }
            } catch (\Exception $e) {
                $cmeAppId = (int) $encCmeAppId;
            }
        }

        \Log::info('[SBI ePay CME] Resolved CME App ID: ' . $cmeAppId);

        $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
            ->where('id', $cmeAppId)
            ->firstOrFail();

        if ($cmeApp->status === 'Approved') {
            \Log::warning('[SBI ePay CME] CME Application already approved for ID: ' . $cmeApp->id);
            return redirect()->route('registration.index')
                ->with('success', 'CME Workshop registration is already approved.');
        }

        $totalAmount = (float) ($cmeApp->total_amount ?: 2360.00);

        try {
            $sbiClient = $this->getSbiClient();

            // Alphanumeric Order Reference
            $orderRef = 'SBICME' . $cmeApp->id . 'T' . time();

            $orderDetails = [
                'mId'            => config('services.sbiepay.mid', '1000003'),
                'currencyCode'   => 'INR',
                'orderAmount'    => $totalAmount,
                'orderRefNumber' => $orderRef,
                'otherDetails'   => json_encode([
                    'payment_type'    => 'CME',
                    'cme_app_id'      => $cmeApp->id,
                    'registration_id' => $cmeApp->registration_id,
                    'user_id'         => $user->id,
                ]),
                'returnUrl'      => $this->getSbiReturnUrl(),
            ];

            \Log::info('[SBI ePay CME] Creating CME Order with Payload:', $orderDetails);

            $response = $sbiClient->order->create($orderDetails);

            if (is_string($response)) {
                $response = json_decode($response, true);
            }

            \Log::info('[SBI ePay CME] Order Create Raw Response:', (array) $response);

            if (isset($response['status']) && $response['status'] == 1 && !empty($response['data'][0]['transactionUrl'])) {
                $transactionUrl = $response['data'][0]['transactionUrl'];
                $sbiOrderRef = $response['data'][0]['sbiOrderRefNumber'] ?? 'N/A';
                $orderHash = $response['data'][0]['orderHash'] ?? 'N/A';

                \Log::info('[SBI ePay CME] Order Created Successfully!', [
                    'orderRefNumber'    => $orderRef,
                    'sbiOrderRefNumber' => $sbiOrderRef,
                    'orderHash'         => $orderHash,
                    'transactionUrl'    => $transactionUrl,
                ]);

                // Create initial pending payment record for CME reconciliation/tracking
                Payment::updateOrCreate(
                    [
                        'registration_id' => $cmeApp->registration_id,
                        'gateway_transaction_id' => $orderRef,
                    ],
                    [
                        'delegate_category_fee'    => 0.00,
                        'accompanying_persons_fee' => 0.00,
                        'cme_fee'                  => 2000.00,
                        'gst_amount'               => 360.00,
                        'total_amount'             => $totalAmount,
                        'currency'                 => 'INR',
                        'payment_method'           => 'SBI_ePay',
                        'payment_status'           => 'Pending',
                        'transaction_id'           => $sbiOrderRef !== 'N/A' ? $sbiOrderRef : null,
                        'gateway_transaction_id'   => $orderRef,
                        'admin_verified'           => false,
                        'payment_date'             => now(),
                    ]
                );

                return view('payment.sbi_redirect', compact('transactionUrl'));
            }

            \Log::error('[SBI ePay CME] Order Creation Failed: ' . json_encode($response));
            $errMsg = $response['errors'][0]['errorMessage'] ?? 'Failed to initiate CME payment.';
            return redirect()->back()->with('error', 'Payment Gateway Error: ' . $errMsg);

        } catch (\Throwable $e) {
            \Log::error('[SBI ePay CME] Exception during CME initiate payment:', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Error initiating CME payment: ' . $e->getMessage());
        }
    }

    /**
     * Handle SBI ePay Callback / Response
     */
    public function sbiResponse(Request $request)
    {
        \Log::info('=== [SBI ePay Callback] Response Received ===', [
            'method'  => $request->method(),
            'url'     => $request->fullUrl(),
            'headers' => $request->headers->all(),
            'query'   => $request->query(),
            'body'    => $request->all(),
            'raw'     => $request->getContent(),
        ]);

        try {
            $sbiClient = $this->getSbiClient();

            // SBI ePay sends encrypted data (query param: encryptedPaymentFinalResponse or body: encData)
            $rawPayload = $request->input('encryptedPaymentFinalResponse') 
                ?? $request->input('encData') 
                ?? $request->input('response') 
                ?? $request->getContent();
            
            $data = $this->decryptSbiPayload($rawPayload);

            // If payload was already parsed or returned in request
            if (!$data && is_array($request->all()) && !empty($request->all())) {
                $data = $request->all();
            }

            if (is_string($data)) {
                $data = json_decode($data, true);
            }

            \Log::info('SBI ePay Decrypted Callback Data:', (array) $data);

            if (!$data) {
                return redirect()->route('payment.failed')
                    ->with('error', 'Unable to process payment response.');
            }

            // Full gateway response JSON to persist in payment record
            $rawGatewayResponse = is_string($data) ? $data : json_encode($data);

            // SBI ePay returns nested structure: [ 0 => [ 'orderInfo' => [...], 'paymentInfo' => [...] ] ]
            if (isset($data[0]) && is_array($data[0])) {
                $data = $data[0];
            }

            $orderInfo = (isset($data['orderInfo']) && is_array($data['orderInfo'])) ? $data['orderInfo'] : [];
            $paymentInfo = (isset($data['paymentInfo']) && is_array($data['paymentInfo'])) ? $data['paymentInfo'] : [];

            // Extract payment details
            $status = strtoupper(trim(
                $paymentInfo['transactionStatus'] 
                ?? $orderInfo['orderStatus'] 
                ?? $data['status'] 
                ?? $data['transactionStatus'] 
                ?? $data['orderStatus'] 
                ?? ''
            ));

            // Extract failure / cancellation message if present
            $statusDesc = $paymentInfo['statusDesc'] 
                ?? $paymentInfo['errorMessage'] 
                ?? $paymentInfo['errorDesc']
                ?? $data['statusDesc'] 
                ?? $data['respMsg'] 
                ?? $data['message'] 
                ?? null;

            $isCancelled = in_array($status, ['CANCEL', 'CANCELLED', 'USER_CANCELLED', 'ABORT', 'ABORTED']) 
                || (is_string($statusDesc) && (stripos($statusDesc, 'cancel') !== false || stripos($statusDesc, 'abort') !== false));

            $failureReason = $isCancelled 
                ? 'Transaction was cancelled by user.' 
                : ($statusDesc ?: 'Payment failed or was declined by bank gateway. Please try again.');

            $orderRef = $orderInfo['orderRefNumber'] 
                ?? $data['orderRefNumber'] 
                ?? $data['merchantOrderNo'] 
                ?? null;

            $sbiOrderRef = $orderInfo['sbiOrderRefNumber'] 
                ?? $paymentInfo['atrnNumber'] 
                ?? $paymentInfo['bankTxnNumber'] 
                ?? $data['sbiOrderRefNumber'] 
                ?? $data['transactionId'] 
                ?? $data['bankTxnId'] 
                ?? null;

            $amount = (float) (
                $paymentInfo['orderAmount'] 
                ?? $paymentInfo['totalAmount'] 
                ?? $data['orderAmount'] 
                ?? $data['amount'] 
                ?? 0
            );

            // Extract details from otherDetails
            $regId = null;
            $isCme = false;
            $cmeAppId = null;

            $rawOtherDetails = $orderInfo['otherDetails'] ?? $data['otherDetails'] ?? null;
            if (!empty($rawOtherDetails)) {
                $otherDetails = is_string($rawOtherDetails) ? json_decode($rawOtherDetails, true) : $rawOtherDetails;
                if (is_string($otherDetails)) {
                    $otherDetails = json_decode($otherDetails, true);
                }
                if (is_array($otherDetails)) {
                    if (($otherDetails['payment_type'] ?? '') === 'CME' || !empty($otherDetails['cme_app_id'])) {
                        $isCme = true;
                        $cmeAppId = $otherDetails['cme_app_id'] ?? null;
                        $regId = $otherDetails['registration_id'] ?? null;
                    } else {
                        $regId = $otherDetails['registration_id'] ?? null;
                    }
                }
            }

            if ($orderRef) {
                if (preg_match('/^SBICME(\d+)T/', $orderRef, $matches)) {
                    $isCme = true;
                    $cmeAppId = $cmeAppId ?: (int) $matches[1];
                } elseif (preg_match('/^SBI(\d+)T/', $orderRef, $matches)) {
                    $regId = $regId ?: (int) $matches[1];
                }
            }

            // Check if status is SUCCESS (Explicitly excluding CREATED/PENDING/CANCEL)
            $isSuccess = in_array($status, ['SUCCESS', 'PAID', 'COMPLETED', 'OTS0000', '0300', 'APPROVED']);

            // === CME PAYMENT FLOW ===
            if ($isCme) {
                $cmeApp = $cmeAppId ? \App\Models\CmeApplication::find($cmeAppId) : null;
                if (!$cmeApp && $regId) {
                    $cmeApp = \App\Models\CmeApplication::where('registration_id', $regId)->latest()->first();
                }

                if (!$cmeApp) {
                    \Log::error('SBI ePay: CME Application not found for response: ' . json_encode($data));
                    return redirect()->route('registration.index')->with('error', 'CME application record not found.');
                }

                // Ensure user session is active for post-payment redirect
                if ($cmeApp->user && !Auth::check()) {
                    Auth::login($cmeApp->user);
                } elseif ($cmeApp->registration?->user && !Auth::check()) {
                    Auth::login($cmeApp->registration->user);
                }

                if ($isSuccess) {
                    $cmeApp->update([
                        'status'         => 'Approved',
                        'transaction_id' => $sbiOrderRef ?? $orderRef,
                        'submitted_at'   => now(),
                        'approved_at'    => now(),
                    ]);

                    $payment = Payment::updateOrCreate(
                        [
                            'registration_id' => $cmeApp->registration_id,
                            'gateway_transaction_id' => $orderRef,
                        ],
                        [
                            'delegate_category_fee'    => 0.00,
                            'accompanying_persons_fee' => 0.00,
                            'cme_fee'                  => 2000.00,
                            'gst_amount'               => 360.00,
                            'total_amount'             => $amount > 0 ? $amount : 2360.00,
                            'currency'                 => 'INR',
                            'transaction_id'           => $sbiOrderRef ?? $orderRef,
                            'gateway_transaction_id'   => $orderRef,
                            'payment_method'           => 'SBI_ePay',
                            'payment_status'           => 'Success',
                            'gateway_response'         => $rawGatewayResponse,
                            'admin_verified'           => true,
                            'payment_date'             => now(),
                        ]
                    );

                    $delegate = Registration::find($cmeApp->registration_id);
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
                                'applicationNumber' => $delegate->registration_number ?? ($delegate->acknowledgement_id ?? $cmeApp->id)
                            ])->setPaper('a4', 'portrait')
                                ->setOption('margin-top', 10)
                                ->setOption('margin-bottom', 10)
                                ->setOption('margin-left', 10)
                                ->setOption('margin-right', 10);

                            $year  = now()->format('Y');
                            $month = now()->format('m');
                            $docName = $delegate->registration_number ?? ($delegate->acknowledgement_id ?? $cmeApp->id);
                            $filename = "Workshop_Receipt_{$docName}.pdf";
                            $cmeReceiptPath = "registrations_receipt/{$year}/{$month}/{$filename}";

                            \Illuminate\Support\Facades\Storage::disk('public')->put($cmeReceiptPath, $pdf->output());

                            $cmeApp->update([
                                'payment_receipt_path' => $cmeReceiptPath,
                            ]);
                        }
                    } catch (\Throwable $pdfEx) {
                        \Log::error('CME Workshop PDF Receipt generation failed: ' . $pdfEx->getMessage());
                    }

                    // Send CME Workshop Confirmation Email with attached PDF
                    try {
                        $recipientEmail = $cmeApp->user?->email ?? ($delegate?->user?->email ?? null);
                        if ($recipientEmail && $delegate) {
                            $userModel = $cmeApp->user ?? $delegate->user;
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

                                if ($cmeReceiptPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($cmeReceiptPath)) {
                                    $localPath = storage_path("app/public/{$cmeReceiptPath}");
                                    $message->attach($localPath, [
                                        'as' => "Pre-Conference Workshop Receipt - {$docId}.pdf",
                                        'mime' => 'application/pdf'
                                    ]);
                                }
                            });

                            \Log::info("CME Workshop Confirmation email sent successfully to: {$recipientEmail}");
                        }
                    } catch (\Throwable $mailEx) {
                        \Log::error('CME Confirmation Email failed in SBI response: ' . $mailEx->getMessage());
                    }

                    return redirect()->route('registration.index')
                        ->with('success', 'Pre-Conference Workshop payment received successfully via SBI ePay! A confirmation email with receipt has been sent.');
                } else {
                    \Log::warning('SBI ePay CME Payment Failed for CME ID: ' . $cmeApp->id . ', Status: ' . $status . ', Reason: ' . $failureReason);

                    Payment::updateOrCreate(
                        [
                            'registration_id' => $cmeApp->registration_id,
                            'gateway_transaction_id' => $orderRef,
                        ],
                        [
                            'delegate_category_fee'    => 0.00,
                            'accompanying_persons_fee' => 0.00,
                            'cme_fee'                  => 2000.00,
                            'gst_amount'               => 360.00,
                            'total_amount'             => $amount > 0 ? $amount : 2360.00,
                            'currency'                 => 'INR',
                            'transaction_id'           => $sbiOrderRef ?? $orderRef,
                            'gateway_transaction_id'   => $orderRef,
                            'payment_method'           => 'SBI_ePay',
                            'payment_status'           => $isCancelled ? 'CANCELLED' : 'Failed',
                            'gateway_response'         => $rawGatewayResponse,
                            'admin_verified'           => false,
                            'payment_date'             => now(),
                        ]
                    );

                    if ($cmeApp->status !== 'Approved') {
                        $cmeApp->update(['status' => 'Pending Payment']);
                    }

                    return redirect()->route('payment.failed', [
                        'type' => 'cme',
                        'cme_id' => $cmeApp->id,
                        'registration' => $cmeApp->registration_id,
                        'txn' => $sbiOrderRef ?? $orderRef,
                        'reason' => $failureReason
                    ])->with('error', $failureReason);
                }
            }

            // === DELEGATE REGISTRATION PAYMENT FLOW ===
            $delegate = null;
            if ($regId) {
                $delegate = Registration::with(['user', 'delegateCategory', 'country', 'state'])->find($regId);
            }

            if (!$delegate) {
                \Log::error('SBI ePay: Registration not found for response: ' . json_encode($data));
                return redirect()->route('login')->with('error', 'Registration record not found.');
            }

            // Ensure user session is active for post-payment pages
            if ($delegate->user && !Auth::check()) {
                Auth::login($delegate->user);
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

            if ($isSuccess) {
                $payment = Payment::updateOrCreate(
                    [
                        'registration_id' => $delegate->id,
                        'gateway_transaction_id' => $orderRef,
                    ],
                    [
                        'delegate_category_fee' => $delFee,
                        'accompanying_persons_fee' => $accFee,
                        'cme_fee' => $cmeFee,
                        'gst_amount' => $gstAmt,
                        'total_amount' => $payAmount,
                        'currency' => 'INR',
                        'transaction_id' => $sbiOrderRef ?? $orderRef,
                        'gateway_transaction_id' => $orderRef,
                        'payment_method' => 'SBI_ePay',
                        'payment_status' => 'Success',
                        'gateway_response' => $rawGatewayResponse,
                        'admin_verified' => true,
                        'payment_date' => now(),
                    ]
                );

                $registrationNo = $delegate->registration_number ?: $delegate->generateRegistrationNumber();
                if (empty($delegate->acknowledgement_id)) {
                    $delegate->acknowledgement_id = $delegate->generateAcknowledgementId();
                }

                $wasAlreadyApproved = ($delegate->status === 'Approved');

                $delegate->updateAmounts();
                $delegate->status = "Approved";
                $delegate->submitted_at = $delegate->submitted_at ?? now();
                $delegate->registration_number = $registrationNo;
                $delegate->approved_at = $delegate->approved_at ?? now();
                $delegate->save();

                // Send emails and generate PDF receipt if not already done
                if (!$wasAlreadyApproved || empty($delegate->registration_pdf_path)) {
                    try {
                        $pdf = PDF::loadView('pdfs.registration', [
                            'registration' => $delegate,
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

                        \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

                        $delegate->update([
                            'registration_pdf_path' => $path,
                        ]);

                        if ($delegate->user && $delegate->user->email) {
                            Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                                $message->to($delegate->user->email)
                                    ->subject('IPHACON 2027 : Delegate Registration Confirmation')
                                    ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027'));

                                $localPath = storage_path("app/public/$path");
                                if (file_exists($localPath)) {
                                    $message->attach($localPath, [
                                        'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                                        'mime' => 'application/pdf'
                                    ]);
                                }
                            });
                        }
                    } catch (\Exception $e) {
                        \Log::error('Receipt Generation/Email failed in SBI response: ' . $e->getMessage());
                    }
                }

                return redirect()->route('payment.success', ['registration' => $delegate->registration_number])
                    ->with('success', 'Payment Received Successfully via SBI ePay!');
            } else {
                \Log::warning('SBI ePay Payment Failed for Registration ID: ' . $delegate->id . ', Status: ' . $status . ', Reason: ' . $failureReason);

                Payment::updateOrCreate(
                    [
                        'registration_id' => $delegate->id,
                        'gateway_transaction_id' => $orderRef,
                    ],
                    [
                        'delegate_category_fee'    => $delFee,
                        'accompanying_persons_fee' => $accFee,
                        'cme_fee'                  => $cmeFee,
                        'gst_amount'               => $gstAmt,
                        'total_amount'             => $payAmount,
                        'currency'                 => 'INR',
                        'transaction_id'           => $sbiOrderRef ?? $orderRef,
                        'gateway_transaction_id'   => $orderRef,
                        'payment_method'           => 'SBI_ePay',
                        'payment_status'           => $isCancelled ? 'CANCELLED' : 'Failed',
                        'gateway_response'         => $rawGatewayResponse,
                        'admin_verified'           => false,
                        'payment_date'             => now(),
                    ]
                );

                if ($delegate->status !== 'Approved') {
                    $delegate->status = 'Pending Payment';
                    $delegate->save();
                }

                return redirect()->route('payment.failed', [
                    'registration' => $delegate->id,
                    'txn' => $sbiOrderRef ?? $orderRef,
                    'reason' => $failureReason
                ])->with('error', $failureReason);
            }

        } catch (\Throwable $e) {
            \Log::error('SBI ePay Callback Exception: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'Error processing SBI payment response: ' . $e->getMessage());
        }
    }


    public function processPayment(Request $request, $registrationId)
    {
        $user = Auth::user();

        $request->validate([
            'transaction_id' => 'required|string|max:100',
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {
            DB::beginTransaction();

            $registration = Registration::where('user_id', $user->id)
                ->where('id', $registrationId)
                ->lockForUpdate()
                ->firstOrFail();

            // If already approved, return without creating duplicate
            if ($registration->status === 'Approved') {
                DB::commit();
                return redirect()->route('registration.index')
                    ->with('success', 'Registration is already approved.');
            }

            // Only send confirmation email on first submission (prevents repeated emails on multiple submits/clicks)
            $isFirstSubmission = empty($registration->submitted_at) || in_array($registration->status, ['Draft', 'Pending Payment']);

            $receiptPath = $request->file('payment_receipt')->store(
                'payment_receipts/' . $registration->id,
                'public'
            );

            $totalAmount = $registration->total_amount ?: $registration->calculateTotalAmount();
            if ($registration->delegate_type === 'International') {
                $delegateCategoryFee = $registration->delegate_fee ?: $totalAmount;
                $accompanyingPersonsFee = 0.00;
                $cmeFee = 0.00;
                $gstAmount = 0.00;
            } else {
                $delegateCategoryFee = $registration->delegate_fee ?: ($registration->delegateCategory ? (float)$registration->delegateCategory->indian_fee : 0.00);
                $accompanyingPersonsFee = $registration->accompanying_fee ?: (($registration->accompanying_persons ?? 0) * 5000.00);
                $cmeFee = $registration->cme_fee ?: ($registration->participate_in_cme ? 2000.00 : 0.00);
                $subtotal = $delegateCategoryFee + $accompanyingPersonsFee + $cmeFee;
                $gstAmount = $registration->gst_amount ?: round($subtotal * 0.18, 2);
                $totalAmount = $registration->total_amount ?: round($subtotal + $gstAmount, 2);
            }

            // Check if payment already exists for this registration to prevent duplicate records
            $payment = Payment::where('registration_id', $registration->id)
                ->where(function($q) use ($request) {
                    $q->where('transaction_id', $request->transaction_id)
                      ->orWhereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION']);
                })
                ->latest()
                ->first();

            $paymentData = [
                'registration_id' => $registration->id,
                'delegate_category_fee' => $delegateCategoryFee,
                'accompanying_persons_fee' => $accompanyingPersonsFee,
                'cme_fee' => $cmeFee,
                'gst_amount' => $gstAmount,
                'total_amount' => $totalAmount,
                'currency' => $registration->delegate_type === 'International' ? 'USD' : 'INR',
                'transaction_id' => $request->transaction_id,
                'payment_method' => 'QR_Code',
                'payment_status' => 'Pending',
                'payment_receipt_path' => $receiptPath,
                'admin_verified' => false
            ];

            if ($payment) {
                $payment->update($paymentData);
            } else {
                $payment = Payment::create($paymentData);
            }

            // Update registration status
            if (empty($registration->acknowledgement_id)) {
                $registration->acknowledgement_id = $registration->generateAcknowledgementId();
            }
            if ($registration->status === 'Draft' || $registration->status === 'Pending Payment') {
                $registration->status = 'Payment Submitted';
            }
            $registration->step_completed = 4;
            $registration->submitted_at = $registration->submitted_at ?? now();
            $registration->save();

            // Record Activity Log
            \App\Models\ActivityLog::record(
                'DELEGATE_REGISTRATION_SUBMITTED',
                "Delegate registration payment submitted for " . ($registration->user?->full_name ?? 'User') . ". Ack ID: {$registration->acknowledgement_id}",
                ['acknowledgement_id' => $registration->acknowledgement_id, 'registration_id' => $registration->id],
                $registration->user
            );

            DB::commit();

            // Send email notification to delegate ONLY upon first registration submission
            if ($isFirstSubmission) {
                try {
                    $recipientEmail = $registration->user?->email;
                    if ($recipientEmail) {
                        $registration->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment']);

                        $ackPdf = null;
                        try {
                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdfs.registration', [
                                'registration' => $registration,
                                'applicationNumber' => $registration->registration_number ?? $registration->acknowledgement_id
                            ])->setPaper('a4', 'portrait')
                                ->setOption('margin-top', 10)
                                ->setOption('margin-bottom', 10)
                                ->setOption('margin-left', 10)
                                ->setOption('margin-right', 10);

                            $ackPdf = $pdf->output();
                        } catch (\Exception $pdfEx) {
                            \Illuminate\Support\Facades\Log::warning('PDF generation during payment submission email failed: ' . $pdfEx->getMessage());
                        }

                        Mail::send('emails.delegate_submission_confirmation', [
                            'registration' => $registration,
                            'user'         => $registration->user,
                            'payment'      => $payment,
                        ], function ($message) use ($recipientEmail, $registration, $ackPdf) {
                            $message->to($recipientEmail)
                                ->subject('IPHACON 2027 : Delegate Registration Submitted (' . $registration->acknowledgement_id . ')')
                                ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027 Secretariat'));

                            if ($ackPdf) {
                                $docId = $registration->registration_number ?? $registration->acknowledgement_id;
                                $message->attachData($ackPdf, "IPHACON_2027_Acknowledgement_Receipt_{$docId}.pdf", [
                                    'mime' => 'application/pdf'
                                ]);
                            }
                        });
                    }
                } catch (\Exception $mailEx) {
                    \Illuminate\Support\Facades\Log::error('Failed to send delegate registration submission confirmation email: ' . $mailEx->getMessage());
                }
            }

            return redirect()->route('registration.index')
                ->with('success', 'Payment proof submitted successfully! Verification is under process.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit payment details: ' . $e->getMessage());
        }
    }

    public function cmeGateway($encCmeAppId = null)
    {
        $user = Auth::user();

        $cmeAppId = null;
        if ($encCmeAppId) {
            if (is_numeric($encCmeAppId)) {
                $cmeAppId = (int) $encCmeAppId;
            } else {
                try {
                    $decrypted = Crypt::decryptString($encCmeAppId);
                    $stepData = json_decode($decrypted, true);
                    if (isset($stepData['cme_app_id'])) {
                        $cmeAppId = (int) $stepData['cme_app_id'];
                    }
                } catch (\Exception $e) {
                    $cmeAppId = (int) $encCmeAppId;
                }
            }
        }

        if ($cmeAppId) {
            $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
                ->where('id', $cmeAppId)
                ->firstOrFail();
        } else {
            $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
                ->latest()
                ->firstOrFail();
        }

        $registration = Registration::where('id', $cmeApp->registration_id)->first();

        return view('payment.cme-gateway', compact('cmeApp', 'registration'));
    }

    public function processCmePayment(Request $request, $cmeAppId)
    {
        $user = Auth::user();

        $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
            ->where('id', $cmeAppId)
            ->firstOrFail();

        $request->validate([
            'transaction_id'  => 'required|string|max:100',
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {
            DB::beginTransaction();

            $receiptPath = $request->file('payment_receipt')->store(
                'cme_receipts/' . $cmeApp->id,
                'public'
            );

            // Update CmeApplication record
            $cmeApp->update([
                'transaction_id'       => $request->transaction_id,
                'payment_receipt_path' => $receiptPath,
                'status'               => 'Payment Submitted',
                'submitted_at'         => now(),
            ]);

            // Check if CME payment already exists to prevent duplicates
            $payment = Payment::where('registration_id', $cmeApp->registration_id)
                ->where(function($q) use ($request) {
                    $q->where('transaction_id', $request->transaction_id)
                      ->orWhere('cme_fee', '>', 0);
                })
                ->whereIn('payment_status', ['Pending', 'Payment Submitted', 'Submitted', 'UNDER_VERIFICATION'])
                ->latest()
                ->first();

            $paymentData = [
                'registration_id'          => $cmeApp->registration_id,
                'delegate_category_fee'    => 0.00,
                'accompanying_persons_fee' => 0.00,
                'cme_fee'                  => 2000.00,
                'gst_amount'               => 360.00,
                'total_amount'             => 2360.00,
                'currency'                 => 'INR',
                'transaction_id'           => $request->transaction_id,
                'payment_method'           => 'QR_Code',
                'payment_status'           => 'Pending',
                'payment_receipt_path'     => $receiptPath,
                'admin_verified'           => false
            ];

            if ($payment) {
                $payment->update($paymentData);
            } else {
                $payment = Payment::create($paymentData);
            }

            DB::commit();

            return redirect()->route('registration.index')
                ->with('success', 'CME Workshop payment proof submitted successfully! Verification is under process.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit CME payment details: ' . $e->getMessage());
        }
    }

    public function success($registrationId)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)
            ->where(function ($q) use ($registrationId) {
                $q->where('registration_number', $registrationId)
                  ->orWhere('id', $registrationId);
            })
            ->with(['delegateCategory', 'country', 'state', 'payments'])
            ->firstOrFail();

        return view('payment.success', compact('registration'));
    }

    public function failed(Request $request, $registrationId = null)
    {
        $user = Auth::user();
        $type = $request->query('type');
        $cmeId = $request->query('cme_id');
        $txn = $request->query('txn');
        $reason = $request->query('reason') ?: session('error');

        $registration = null;
        $cmeApp = null;
        $failedPayment = null;

        // 1. Check if this is a CME Workshop failure
        if ($type === 'cme' || $cmeId) {
            if ($cmeId && $user) {
                $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
                    ->where('id', $cmeId)
                    ->first();
            }
            if ($cmeApp) {
                $registration = Registration::with(['delegateCategory', 'country', 'state'])
                    ->find($cmeApp->registration_id);

                $failedPayment = Payment::where('registration_id', $cmeApp->registration_id)
                    ->whereIn('payment_status', ['Failed', 'CANCELLED'])
                    ->latest('id')
                    ->first();
            }
        }

        // 2. Delegate registration payment failure
        if (!$registration) {
            $regQuery = Registration::with(['delegateCategory', 'country', 'state', 'payments']);

            if ($user) {
                $regQuery->where('user_id', $user->id);
            }

            if ($registrationId) {
                if (is_numeric($registrationId)) {
                    $registration = $regQuery->where('id', (int)$registrationId)->first();
                } else {
                    $registration = $regQuery->where(function ($q) use ($registrationId) {
                        $q->where('registration_number', $registrationId)
                          ->orWhere('acknowledgement_id', $registrationId);
                    })->first();
                }
            }

            // Fallback: If no registration found or ID not provided, fetch user's latest pending/draft registration
            if (!$registration && $user) {
                $registration = Registration::where('user_id', $user->id)
                    ->whereIn('status', ['Pending Payment', 'Draft'])
                    ->with(['delegateCategory', 'country', 'state', 'payments'])
                    ->latest()
                    ->first();
            }

            if ($registration) {
                $failedPayment = $registration->payments()
                    ->whereIn('payment_status', ['Failed', 'CANCELLED'])
                    ->latest('id')
                    ->first();
            }
        }

        if (empty($reason)) {
            $reason = 'The payment transaction could not be completed or was cancelled.';
        }

        return view('payment.failed', compact('registration', 'cmeApp', 'failedPayment', 'txn', 'reason', 'type'));
    }
}
