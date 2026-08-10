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

    public function gateway($encRegistrationId)
    {
        $user = Auth::user();

        $decrypted = Crypt::decryptString($encRegistrationId);
        $stepData = json_decode($decrypted, true);

        if (!isset($stepData['reg_id'], $stepData['uid'])) {
            abort(404);
        }

        $registrationId = (int) $stepData['reg_id'];
        $uid = (int) $stepData['uid'];

        $registration = Registration::where('user_id', $user->id)
            ->where('id', $registrationId)
            ->with(['delegateCategory', 'country', 'state'])
            ->firstOrFail();

        // Check if registration is ready for payment
        if ($registration->status !== 'Draft' && $registration->step_completed < 3) {
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

        $login = "738775";
        $password = "c7c909aa";
        $product_id = "ASSOCIATION";
        $date = date('Y-m-d H:i:s'); // current date
        $encRequestKey = "FECC5464AF23EB279B7E1A3A746043E4";
        $decResponseKey = "4AE12A7794F02329204685C298B4EDF0";
        $api_url = "https://payment1.atomtech.in/ots/aipay/auth";
        $user_email = auth()->user()->email;
        $user_contact_number = auth()->user()->mobile_number;
        $return_url = "https://registration.iphacon2027.com/api/response";

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

                $delegate = Registration::with([
                    'user',
                    'latestPayment'
                ])->where('id', $jsonData['payInstrument']['extras']['udf1'])
                    ->latest('created_at')->first();

                $payAmount = (float) ($jsonData['payInstrument']['payDetails']['amount'] ?? 0);
                if ($delegate && $delegate->delegate_type === 'International') {
                    $delFee = $payAmount;
                    $gstAmt = 0.00;
                } else {
                    $delFee = round($payAmount / 1.18, 2);
                    $gstAmt = round($payAmount - $delFee, 2);
                }

                $payment = new Payment([
                    'registration_id' => $jsonData['payInstrument']['extras']['udf1'],
                    'delegate_category_fee' => $delFee,
                    'accompanying_persons_fee' => 0.00,
                    'cme_fee' => 0.00,
                    'gst_amount' => $gstAmt,
                    'total_amount' => $payAmount,
                    'currency' => 'INR',
                    'transaction_id' => $jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'] ?? null,
                    // 'gateway_response' => $jsonData,
                    'gateway_transaction_id' => $jsonData['payInstrument']['merchDetails']['merchTxnId'] ?? null,
                    'payment_method' => 'Gateway',
                    'payment_status' => 'Success',
                ]);
                
                $payment->save();

                $registrationNo = $delegate->generateRegistrationNumber();

                $pdf = PDF::loadView('pdfs.registration', [
                    'registration' => $delegate,
                    'applicationNumber' => $delegate->registration_number
                ])->setPaper('a4', 'portrait')
                    ->setOption('margin-top', 10)
                    ->setOption('margin-bottom', 10)
                    ->setOption('margin-left', 10)
                    ->setOption('margin-right', 10);

                $filename = "Delegate_Registration_{$delegate->registration_number}.pdf";
                // $path = "registrations_receipt/{$delegate->registration_number}/{$filename}";

                $year  = now()->format('Y');   // 2026
                $month = now()->format('m');   // 04

                $path = "registrations_receipt/{$year}/{$month}/{$filename}";

                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $pdf->output());

                $delegate->updateAmounts();
                $delegate->update([
                    'status' => "Approved",
                    'registration_pdf_path' => $path,
                    'registration_number' => $registrationNo,
                ]);

                try {
                    Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                        $message->to($delegate->user->email)
                            ->subject('IPHACON 2027 : Delegate Registration Confirmation')
                            ->from(config('mail.from.address', 'noreply@iphacon2027.com'), config('mail.from.name', 'IPHACON 2027'));

                        // Attach the PDF file
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

                        // Attach the PDF file
                        $localPath = storage_path("app/public/$path");

                        $message->attach($localPath, [
                            'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                            'mime' => 'application/pdf'
                        ]);
                    });
                } catch (\Exception $e) {
                    \Log::error('Mail sending failed: ' . $e->getMessage());

                    return response()->json([
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ]);
                }

                // dd($jsonData['payInstrument']);

                return redirect()->route('payment.success', ['registration' => $delegate->registration_number])
                    ->with('success', 'Payment Successfully Received!');

                exit;

                // $salt = 'vikash_goswami';
                // $encoded_app = base64_encode(strrev($jsonData['payInstrument']['extras']['udf2']) . $salt);
                // $encoded_email = base64_encode(strrev($student->email) . $salt);
                // $encoded_path = base64_encode(strrev($student->registration_pdf_path) . $salt);
                // $encoded_fname = base64_encode(strrev($student->first_name) . $salt);
                // $encoded_class = base64_encode(strrev($student->apply_class) . $salt);
                // $data = [
                //     'ap' => $encoded_app,
                //     'ai' => $encoded_email,
                //     'th' => $encoded_path,
                //     'an' => $encoded_fname,
                //     'as' => $encoded_class
                // ];

                // $queryString = http_build_query($data);

                // header("Location: http://localhost/project/admn/regn_confirm.php?$queryString");


                break;
            default:
                echo 'Payment status = Transaction Failed';
                break;
        }
    }


    public function processPayment(Request $request, $registrationId)
    {
        $user = Auth::user();
        $registration = Registration::where('user_id', $user->id)
            ->where('id', $registrationId)
            ->firstOrFail();

        $request->validate([
            'transaction_id' => 'required|string|max:100',
            'payment_receipt' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {
            DB::beginTransaction();

            $receiptPath = $request->file('payment_receipt')->store(
                'payment_receipts/' . $registration->id,
                'public'
            );

            $totalAmount = $registration->calculateTotalAmount();
            if ($registration->delegate_type === 'International') {
                $delegateCategoryFee = $totalAmount;
                $accompanyingPersonsFee = 0.00;
                $cmeFee = 0.00;
                $gstAmount = 0.00;
            } else {
                $delegateCategoryFee = round($totalAmount / 1.18, 2);
                $accompanyingPersonsFee = ($registration->accompanying_persons ?? 0) * 5000;
                $cmeFee = $registration->participate_in_cme ? 2000 : 0;
                $gstAmount = round($totalAmount - $delegateCategoryFee, 2);
            }

            // Create payment record
            $payment = new Payment([
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
            ]);

            $payment->save();

            // Update registration status
            if (empty($registration->acknowledgement_id)) {
                $registration->acknowledgement_id = $registration->generateAcknowledgementId();
            }
            if ($registration->status === 'Draft') {
                $registration->status = 'Payment Submitted';
            }
            $registration->step_completed = 4;
            $registration->submitted_at = $registration->submitted_at ?? now();
            $registration->save();

            DB::commit();

            return redirect()->route('registration.index')
                ->with('success', 'Payment proof submitted successfully! Verification is under process.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit payment details: ' . $e->getMessage());
        }
    }

    public function cmeGateway($encCmeAppId)
    {
        $user = Auth::user();

        $decrypted = Crypt::decryptString($encCmeAppId);
        $stepData = json_decode($decrypted, true);

        if (!isset($stepData['cme_app_id'], $stepData['uid'])) {
            abort(404);
        }

        $cmeAppId = (int) $stepData['cme_app_id'];

        $cmeApp = \App\Models\CmeApplication::where('user_id', $user->id)
            ->where('id', $cmeAppId)
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

            // Create explicit payment record for CME
            $payment = new Payment([
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
            ]);

            $payment->save();

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
