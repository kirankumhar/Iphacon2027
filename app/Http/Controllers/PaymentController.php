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

class PaymentController extends Controller
{

    public function gateway()
    {
        $user = Auth::user();

        $registration = Registration::where('user_id', $user->id)
            ->whereIn('status', ['Draft', 'Pending Payment'])
            ->with(['delegateCategory', 'country', 'state'])
            ->latest()
            ->firstOrFail();

        // Check if registration is ready for payment
        if ($registration->status !== 'Draft' && $registration->status !== 'Pending Payment' && $registration->step_completed < 3) {
            return redirect()->route('registration.create')
                ->with('error', 'Please complete all registration steps before payment.');
        }

        $data = $this->index($registration);

        return view('payment.gateway', compact('registration', 'data'));
    }

    public function index($registration)
    {
        $merchTxnId = 'CONF' . $registration->id . '_' . time();
        $totalAmount = $registration->user_id == 12 ? 30 : $registration->calculateTotalAmount();

        $login = "";
        $password = "";
        $product_id = "";
        $date = date('Y-m-d H:i:s'); // current date
        $encRequestKey = "";
        $decResponseKey = "";
        $api_url = "";
        $user_email = auth()->user()->email;
        $user_contact_number = auth()->user()->mobile_number;
        $return_url = "";

        $payData = array(
            'login' => $login,
            'password' => $password,
            'amount' => $totalAmount,
            'prod_id' => $product_id,
            'txnId' => $merchTxnId,
            'date' => $date,
            'encKey' => $encRequestKey,
            'decKey' => $decResponseKey,
            'payUrl' => $api_url,
            'email' => $user_email,
            'mobile' => $user_contact_number,
            'txnCurrency' => 'INR',
            'return_url' => $return_url,
            'udf1' => $registration->id,  // optional
            'udf2' => "",  // optional
            'udf3' => "",  // optional
            'udf4' => "",  // optional
            'udf5' => ""   // optional
        );

        $atomTokenId = $this->createTokenId($payData);

        $data = array(
            'login' => $login,
            'amount' => $totalAmount,
            'txnId' => $merchTxnId,
            'mid' => 738775,
            'email' => $user_email,
            'mobile' => $user_contact_number,
            'atomTokenId' => $atomTokenId,
            'return_url' => $return_url,
        );

        return $data;

        // return view('atompay')->with('data', $data)
        //     ->with('atomTokenId', $atomTokenId);
    }

    //do not change anything in below function
    public function createTokenId($data)
    {

        $jsondata = '{
                 "payInstrument": {
                     "headDetails": {
                         "version": "OTSv1.1",
                         "api": "AUTH",
                         "platform": "FLASH"
                     },
                     "merchDetails": {
                         "merchId": "' . $data['login'] . '",
                         "userId": "",
                         "password": "' . $data['password'] . '",
                         "merchTxnId": "' . $data['txnId'] . '",
                         "merchTxnDate": "' . $data['date'] . '"
                     },
                     "payDetails": {
                         "amount": "' . $data['amount'] . '",
                         "product": "' . $data['prod_id'] . '",
                         "custAccNo": "44249278159",
                         "txnCurrency": "' . $data['txnCurrency'] . '"
                     },
                     "custDetails": {
                         "custEmail": "' . $data['email'] . '",
                         "custMobile": "' . $data['mobile'] . '"
                     },
                     "extras": {
                         "udf1": "' . $data['udf1'] . '",
                         "udf2": "' . $data['udf2'] . '",
                         "udf3": "' . $data['udf3'] . '",
                         "udf4": "' . $data['udf4'] . '",
                         "udf5": "' . $data['udf5'] . '"
                     }
                 }
             }';
        $encData = $this->encrypt($jsondata, $data['encKey'], $data['encKey']);

        $curl = curl_init();
        $curlOpts = array(
            CURLOPT_URL => $data['payUrl'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "encData=" . $encData . "&merchId=" . $data['login'],
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        );

        $caPath = dirname(__FILE__) . '/cacert.pem';
        if (file_exists($caPath)) {
            $curlOpts[CURLOPT_CAINFO] = $caPath;
            $curlOpts[CURLOPT_SSL_VERIFYHOST] = 2;
            $curlOpts[CURLOPT_SSL_VERIFYPEER] = true;
        }

        curl_setopt_array($curl, $curlOpts);
        $atomTokenId = null;
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            \Log::error("Atom Payment cURL Error: " . $error_msg);
            curl_close($curl);
            return null;
        }
        curl_close($curl);

        if (!$response) {
            \Log::error("Atom Payment Empty Response");
            return null;
        }

        $getresp = explode("&", $response);
        if (!isset($getresp[1])) {
            \Log::error("Atom Payment Invalid Response Format: " . $response);
            return null;
        }

        $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);
        $decData = $this->decrypt($encresp, $data['decKey'], $data['decKey']);
        
        $res = json_decode($decData, true);
        if ($res && isset($res['responseDetails']['txnStatusCode']) && $res['responseDetails']['txnStatusCode'] == 'OTS0000') {
            $atomTokenId = $res['atomTokenId'] ?? null;
        } else {
            \Log::error("Atom Payment Auth Failed: " . ($decData ?? 'No decData'));
            $atomTokenId = null;
        }

        return $atomTokenId;
    }

    //do not change anything in below function
    public function encrypt($data, $salt, $key)
    {
        $method = "AES-256-CBC";
        $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $chars = array_map("chr", $iv);
        $IVbytes = join($chars);
        $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
        $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
        $hash = openssl_pbkdf2($key1, $salt1, '256', '65536', 'sha512');
        $encrypted = openssl_encrypt($data, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
        return strtoupper(bin2hex($encrypted));
    }

    //do not change anything in below function
    public function decrypt($data, $salt, $key)
    {
        $dataEncypted = hex2bin($data);
        $method = "AES-256-CBC";
        $iv = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $chars = array_map("chr", $iv);
        $IVbytes = join($chars);
        $salt1 = mb_convert_encoding($salt, "UTF-8"); //Encoding to UTF-8
        $key1 = mb_convert_encoding($key, "UTF-8"); //Encoding to UTF-8
        $hash = openssl_pbkdf2($key1, $salt1, '256', '65536', 'sha512');
        $decrypted = openssl_decrypt($dataEncypted, $method, $hash, OPENSSL_RAW_DATA, $IVbytes);
        return $decrypted;
    }

    public function response()
    {
        $data = $_POST['encData'];

        // change decryption key below for production
        $decData = $this->decrypt($data, '4AE12A7794F02329204685C298B4EDF0', '4AE12A7794F02329204685C298B4EDF0');
        $jsonData = json_decode($decData, true);

        switch ($jsonData['payInstrument']['responseDetails']['statusCode']) {
            case 'OTS0000':
                $regId = $jsonData['payInstrument']['extras']['udf1'];
                $bankTxnId = $jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'] ?? null;
                $merchTxnId = $jsonData['payInstrument']['merchDetails']['merchTxnId'] ?? null;

                $delegate = Registration::with([
                    'user',
                    'latestPayment'
                ])->where('id', $regId)
                    ->latest('created_at')->first();

                if (!$delegate) {
                    return redirect()->route('login');
                }

                $payAmount = (float) ($jsonData['payInstrument']['payDetails']['amount'] ?? 0);
                if ($delegate && $delegate->delegate_type === 'International') {
                    $delFee = $delegate->delegate_fee ?: $payAmount;
                    $cmeFee = 0.00;
                    $accFee = 0.00;
                    $gstAmt = 0.00;
                } else {
                    $delFee = $delegate ? ($delegate->delegate_fee ?: ($delegate->delegateCategory ? (float)$delegate->delegateCategory->indian_fee : 0.00)) : round($payAmount / 1.18, 2);
                    $cmeFee = $delegate ? ($delegate->cme_fee ?: ($delegate->participate_in_cme ? 2000.00 : 0.00)) : 0.00;
                    $accFee = $delegate ? ($delegate->accompanying_fee ?: (($delegate->accompanying_persons ?? 0) * 5000.00)) : 0.00;
                    $subtotal = $delFee + $cmeFee + $accFee;
                    $gstAmt = $delegate ? ($delegate->gst_amount ?: round($subtotal * 0.18, 2)) : round($payAmount - $delFee, 2);
                }

                // Check for existing payment to prevent duplicate records
                $existingPayment = Payment::where('registration_id', $regId)
                    ->where(function($q) use ($bankTxnId, $merchTxnId) {
                        if ($bankTxnId) {
                            $q->where('transaction_id', $bankTxnId);
                        }
                        if ($merchTxnId) {
                            $q->orWhere('gateway_transaction_id', $merchTxnId);
                        }
                    })->first();

                $paymentData = [
                    'registration_id' => $regId,
                    'delegate_category_fee' => $delFee,
                    'accompanying_persons_fee' => $accFee,
                    'cme_fee' => $cmeFee,
                    'gst_amount' => $gstAmt,
                    'total_amount' => $payAmount,
                    'currency' => 'INR',
                    'transaction_id' => $bankTxnId,
                    'gateway_transaction_id' => $merchTxnId,
                    'payment_method' => 'Gateway',
                    'payment_status' => 'Success',
                    'admin_verified' => true,
                    'payment_date' => now(),
                ];

                if ($existingPayment) {
                    $existingPayment->update($paymentData);
                    $payment = $existingPayment;
                } else {
                    $payment = Payment::create($paymentData);
                }

                $registrationNo = $delegate->registration_number ?: $delegate->generateRegistrationNumber();
                if (empty($delegate->acknowledgement_id)) {
                    $delegate->acknowledgement_id = $delegate->generateAcknowledgementId();
                }

                $wasAlreadyApproved = ($delegate->status === 'Approved');

                $delegate->updateAmounts();
                $delegate->status = "Approved";
                $delegate->registration_number = $registrationNo;
                $delegate->approved_at = $delegate->approved_at ?? now();
                $delegate->save();

                $delegate->loadMissing(['user', 'delegateCategory', 'country', 'state', 'latestPayment']);

                // Only generate and send emails if not already approved to avoid duplicate emails
                if (!$wasAlreadyApproved || empty($delegate->registration_pdf_path)) {
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

                    try {
                        Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                            $message->to($delegate->user->email)
                                ->subject('IPHACON 2027 : Delegate Registration Confirmation')
                                ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027'));

                            $localPath = storage_path("app/public/$path");
                            $message->attach($localPath, [
                                'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                                'mime' => 'application/pdf'
                            ]);
                        });

                        Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                            $message->to("iphacon2027@gmail.com")
                                ->subject('IPHACON 2027 : Delegate Registration Confirmation')
                                ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027'));

                            $localPath = storage_path("app/public/$path");
                            $message->attach($localPath, [
                                'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                                'mime' => 'application/pdf'
                            ]);
                        });
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed: ' . $e->getMessage());
                    }
                }

                return redirect()->route('payment.success', ['registration' => $delegate->registration_number])
                    ->with('success', 'Payment Successfully Received!');

                break;
            default:
                echo 'Payment status = Transaction Failed';
                break;
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

    public function cmeGateway()
    {
        $user = Auth::user();

        $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
            ->where('status', 'Pending Payment')
            ->latest()
            ->firstOrFail();

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
            ->where('registration_number', $registrationId)
            ->with(['delegateCategory', 'country', 'state', 'payments'])
            ->firstOrFail();

        return view('payment.success', compact('registration'));
    }

    public function failed($registrationId = null)
    {
        $registration = null;

        if ($registrationId) {
            $user = Auth::user();
            $registration = Registration::where('user_id', $user->id)
                ->where('id', $registrationId)
                ->firstOrFail();
        }

        return view('payment.failed', compact('registration'));
    }
}
