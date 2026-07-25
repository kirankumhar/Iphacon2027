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
        $return_url = "https://registration.ismmconference.com/api/response";

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
        curl_setopt_array($curl, array(
            CURLOPT_URL => $data['payUrl'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_CAINFO => dirname(__FILE__) . '/cacert.pem', //added in Controllers folder
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "encData=" . $encData . "&merchId=" . $data['login'],
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));
        $atomTokenId = null;
        $response = curl_exec($curl);

        // return response()->json(['success' => true, 'msg' => $response]);

        $getresp = explode("&", $response);

        $encresp = substr($getresp[1], strpos($getresp[1], "=") + 1);
        $decData = $this->decrypt($encresp, $data['decKey'], $data['decKey']);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            echo "error = " . $error_msg;
        }
        if (isset($error_msg)) {
            echo "error = " . $error_msg;
        }
        curl_close($curl);
        $res = json_decode($decData, true);
        if ($res) {
            if ($res['responseDetails']['txnStatusCode'] == 'OTS0000') {
                $atomTokenId = $res['atomTokenId'];
            } else {
                echo "Error getting data";
                $atomTokenId = null;
            }
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

                $payment = new Payment([
                    'registration_id' => $jsonData['payInstrument']['extras']['udf1'],
                    'delegate_category_fee' => 0.00,
                    'accompanying_persons_fee' => 0.00,
                    'cme_fee' => 0.00,
                    'total_amount' => $jsonData['payInstrument']['payDetails']['amount'],
                    'currency' => 'INR',
                    'transaction_id' => $jsonData['payInstrument']['payModeSpecificData']['bankDetails']['bankTxnId'],
                    // 'gateway_response' => $jsonData,
                    'gateway_transaction_id' => $jsonData['payInstrument']['merchDetails']['merchTxnId'],
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

                $delegate->update([
                    'id' => $delegate->id,
                    'status' => "Approved",
                    'registration_pdf_path' => $path,
                    'registration_number' => $registrationNo,
                    'total_amount' => $jsonData['payInstrument']['payDetails']['amount'],
                ]);

                try {
                    Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                        $message->to($delegate->user->email)
                            ->subject('ISMM : Delegate Registration Confirmation')
                            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                        // Attach the PDF file
                        $localPath = storage_path("app/public/$path");

                        $message->attach($localPath, [
                            'as' => "Delegate Registration - {$delegate->registration_number}.pdf",
                            'mime' => 'application/pdf'
                        ]);
                    });

                    Mail::send('emails.registration_confirmation', ['registration' => $delegate, 'registrationID' => $delegate->registration_number], function ($message) use ($delegate, $path) {
                        $message->to("ismm2027@gmail.com")
                            ->subject('ISMM : Delegate Registration Confirmation')
                            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

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
