<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use Sbiepay\utils\crypto\Aes;

class SbiTestController extends Controller
{
    /**
     * Diagnostic endpoint to check SBI ePay credentials, server IP, and API connectivity.
     */
    public function checkKeys(Request $request)
    {
        $currentEnv = strtoupper(config('services.sbiepay.env', 'SANDBOX'));
        $mid = config('services.sbiepay.mid');
        $apiKey = config('services.sbiepay.api_key');
        $apiSecret = config('services.sbiepay.api_secret');
        $encKey = config('services.sbiepay.encryption_key');

        // 1. Detect Server Outgoing Public IP
        $serverOutgoingIp = $this->getServerOutgoingIp();

        // 2. Test Encryption Key
        $encryptionTest = $this->testEncryption($encKey);

        // 3. Test SBI Access Token API for LIVE and SANDBOX
        $liveResult = $this->testSbiToken('https://sbiepay.sbi.bank.in/api/transaction/v1/token/access', $apiKey, $apiSecret);
        $sandboxResult = $this->testSbiToken('https://integration.sbiepay.sbiuat.bank.in/api/transaction/v1/token/access', $apiKey, $apiSecret);

        // 4. Evaluate Active Environment
        $activeTest = ($currentEnv === 'LIVE') ? $liveResult : $sandboxResult;

        $overallStatus = ($activeTest['http_status'] === 200 && ($activeTest['sbi_status'] ?? 0) === 1) ? 'SUCCESS' : 'FAILED';

        return response()->json([
            'status' => $overallStatus,
            'message' => $overallStatus === 'SUCCESS' 
                ? 'SBI ePay credentials verified successfully for ' . $currentEnv . ' environment!'
                : 'SBI ePay verification failed for ' . $currentEnv . ' environment. See details below.',
            
            'server_info' => [
                'server_outgoing_public_ip' => $serverOutgoingIp,
                'ip_whitelisting_note' => 'Share this exact Outgoing Public IP with SBI if getting HTTP 403 Access Restricted.',
            ],

            'current_config' => [
                'SBIEPAY_ENV' => $currentEnv,
                'SBIEPAY_MID' => $mid,
                'SBIEPAY_API_KEY' => $this->maskString($apiKey),
                'SBIEPAY_API_KEY_LENGTH' => strlen($apiKey ?? ''),
                'SBIEPAY_API_SECRET' => $this->maskString($apiSecret),
                'SBIEPAY_API_SECRET_LENGTH' => strlen($apiSecret ?? ''),
                'SBIEPAY_ENCRYPTION_KEY' => $this->maskString($encKey),
                'SBIEPAY_ENCRYPTION_KEY_LENGTH' => strlen($encKey ?? ''),
                'encryption_key_working' => $encryptionTest['success'],
                'encryption_test_error' => $encryptionTest['error'] ?? null,
            ],

            'active_environment_test' => [
                'environment' => $currentEnv,
                'result' => $activeTest,
            ],

            'all_environments_comparison' => [
                'LIVE_ENDPOINT' => $liveResult,
                'SANDBOX_ENDPOINT' => $sandboxResult,
            ],

            'troubleshooting_hints' => $this->getHints($activeTest, $serverOutgoingIp, $currentEnv),
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Get Server Outbound Public IP
     */
    private function getServerOutgoingIp(): string
    {
        try {
            $response = Http::timeout(4)->get('https://api.ipify.org?format=json');
            if ($response->successful()) {
                return $response->json('ip') ?? 'Unknown';
            }
        } catch (\Throwable $e) {
            // Fallback attempt
            try {
                $response2 = Http::timeout(3)->get('https://icanhazip.com');
                if ($response2->successful()) {
                    return trim($response2->body());
                }
            } catch (\Throwable $e2) {
                // Ignore
            }
        }

        return 'Could not detect automatically';
    }

    /**
     * Test AES Encryption & Decryption
     */
    private function testEncryption(?string $encKey): array
    {
        if (empty($encKey)) {
            return ['success' => false, 'error' => 'Encryption key is empty'];
        }

        try {
            $sampleText = "IPHACON_TEST_" . time();
            $encrypted = Aes::encrypt($encKey, $sampleText);
            $decrypted = Aes::decrypt($encKey, $encrypted);

            if ($decrypted === $sampleText) {
                return ['success' => true];
            }

            return ['success' => false, 'error' => 'Decrypted string did not match original'];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Direct test to SBI Access Token endpoint
     */
    private function testSbiToken(string $url, ?string $apiKey, ?string $apiSecret): array
    {
        if (empty($apiKey) || empty($apiSecret)) {
            return [
                'http_status' => null,
                'error' => 'API Key or Secret is missing in config',
            ];
        }

        $client = new Client(['verify' => false, 'timeout' => 10]);

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type'            => 'application/json',
                    'Accept'                  => 'application/json',
                    'Merchant-API-Key-Id'     => $apiKey,
                    'Merchant-API-Key-Secret' => $apiSecret,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            return [
                'endpoint'    => $url,
                'http_status' => $statusCode,
                'sbi_status'  => $decoded['status'] ?? $decoded['Status'] ?? null,
                'response'    => $decoded ?? $body,
            ];

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = (string) $e->getResponse()->getBody();
                $decoded = json_decode($body, true);

                $msg = $decoded ?? substr(strip_tags($body), 0, 500);

                if ($statusCode === 403 || stripos($body, 'Access Restricted') !== false) {
                    return [
                        'endpoint'    => $url,
                        'http_status' => 403,
                        'error_type'  => 'HTTP 403 (Access Restricted)',
                        'message'     => 'Server/IP is not whitelisted by SBI ePay or credentials are unauthorized.',
                        'raw'         => $msg,
                    ];
                }

                return [
                    'endpoint'    => $url,
                    'http_status' => $statusCode,
                    'response'    => $msg,
                ];
            }

            return [
                'endpoint'    => $url,
                'http_status' => null,
                'error'       => 'Request error: ' . $e->getMessage(),
            ];

        } catch (ConnectException $e) {
            return [
                'endpoint'    => $url,
                'http_status' => null,
                'error'       => 'Connection/Network timeout: ' . $e->getMessage(),
            ];
        } catch (\Throwable $e) {
            return [
                'endpoint'    => $url,
                'http_status' => null,
                'error'       => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Provide actionable troubleshooting hints
     */
    private function getHints(array $test, string $serverIp, string $currentEnv): array
    {
        $hints = [];

        if (($test['http_status'] ?? null) === 403) {
            $hints[] = "HTTP 403 Forbidden: Your server IP ({$serverIp}) is not whitelisted in SBI ePay firewall. Contact SBI technical support (epay.support@sbi.co.in) to whitelist this IP.";
        }

        $resStr = json_encode($test);
        if (strpos($resStr, '1003') !== false || strpos($resStr, 'was not found') !== false) {
            $hints[] = "Error 1003: API Key Id and API Key Secret was not found. If this is LIVE keys, verify SBIEPAY_ENV is set to LIVE and run 'php artisan config:clear'.";
        }

        if ($currentEnv === 'SANDBOX') {
            $hints[] = "Currently SBIEPAY_ENV is set to SANDBOX. If testing with production bank keys, change SBIEPAY_ENV=LIVE in .env.";
        }

        if (empty($hints)) {
            $hints[] = "Configuration and connection are working fine!";
        }

        return $hints;
    }

    /**
     * Hit SBI ePay Order Create API directly with dummy/custom payload
     * and return the payment redirection URL.
     */
    public function createTestOrder(Request $request)
    {
        $currentEnv = strtoupper(config('services.sbiepay.env', 'LIVE'));
        $mid = config('services.sbiepay.mid');
        $apiKey = config('services.sbiepay.api_key');
        $apiSecret = config('services.sbiepay.api_secret');
        $encKey = config('services.sbiepay.encryption_key');

        $amount = (float) ($request->input('amount') ?? 10.00);
        $orderRef = $request->input('order_ref') ?? ('TEST' . time());
        $returnUrl = $request->input('return_url') ?? (config('services.sbiepay.return_url') ?: url('/payment/sbi/response'));
        $customerName = $request->input('customer_name') ?? 'Test Delegate';
        $customerEmail = $request->input('customer_email') ?? 'test@iphacon2027.com';

        $payload = [
            'mId'            => $mid,
            'currencyCode'   => 'INR',
            'orderAmount'    => $amount,
            'orderRefNumber' => $orderRef,
            'otherDetails'   => json_encode([
                'name'         => $customerName,
                'email'        => $customerEmail,
                'is_test'      => true,
                'initiated_at' => now()->toIso8601String(),
            ]),
            'returnUrl'      => $returnUrl,
        ];

        try {
            $sbiClient = new \Sbiepay\SBIEPayClient([
                'apiKey'        => $apiKey,
                'apiSecret'     => $apiSecret,
                'encryptionKey' => $encKey,
            ], $currentEnv, 'JSON', true);

            $response = $sbiClient->order->create($payload);

            if (is_string($response)) {
                $response = json_decode($response, true);
            }

            if (isset($response['status']) && (int)$response['status'] === 1 && !empty($response['data'][0]['transactionUrl'])) {
                $transactionUrl = $response['data'][0]['transactionUrl'];
                $sbiOrderRef = $response['data'][0]['sbiOrderRefNumber'] ?? null;
                $orderHash = $response['data'][0]['orderHash'] ?? null;

                return response()->json([
                    'status'               => 'SUCCESS',
                    'message'              => 'SBI ePay order created successfully!',
                    'payment_url'          => $transactionUrl,
                    'order_ref_number'     => $orderRef,
                    'sbi_order_ref_number' => $sbiOrderRef,
                    'order_hash'           => $orderHash,
                    'amount'               => $amount,
                    'currency'             => 'INR',
                    'environment'          => $currentEnv,
                    'raw_response'         => $response,
                ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            }

            return response()->json([
                'status'       => 'FAILED',
                'message'      => 'SBI ePay rejected order creation.',
                'error'        => $response['errors'][0]['errorMessage'] ?? 'Unknown gateway error',
                'raw_response' => $response,
            ], 400, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        } catch (\Throwable $e) {
            return response()->json([
                'status'    => 'FAILED',
                'message'   => 'Exception during SBI order creation: ' . $e->getMessage(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
            ], 500, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
    }

    /**
     * Mask sensitive strings (show first 3 and last 3 chars)
     */
    private function maskString(?string $str): string
    {
        if (empty($str)) {
            return 'NOT_SET';
        }
        $len = strlen($str);
        if ($len <= 6) {
            return str_repeat('*', $len);
        }
        return substr($str, 0, 3) . str_repeat('*', $len - 6) . substr($str, -3);
    }
}

