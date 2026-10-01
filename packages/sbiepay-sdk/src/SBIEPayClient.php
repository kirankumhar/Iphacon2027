<?php

namespace Sbiepay;

use Exception;
use GuzzleHttp\Client;
use Sbiepay\exception\SBIEpayException;
use Sbiepay\modules\Crypto;
use Sbiepay\modules\Customer;
use Sbiepay\modules\Order;
use Sbiepay\modules\Refund;
use Sbiepay\traits\ApiHelper;
use Sbiepay\utils\Constants;
use Sbiepay\utils\Logger;

class SBIEPayClient
{
    public Customer $customer;
    public Order $order;
    public Refund $refund;
    public Crypto $crypto;

    protected array $config;
    protected Client $client;
    use ApiHelper;

    /**
     * @param array|object $credentials
     * @param string       $environment  'LIVE' or 'SANDBOX'
     * @param bool         $logging
     */
    public function __construct($credentials, string $environment = 'LIVE', string $responseType = 'JSON', bool $logging = false)
    {
        try {
        $credentials = (array) $credentials;

        // Validate credentials
        $required = ['apiKey', 'apiSecret', 'encryptionKey'];
        foreach ($required as $field) {
            if (empty($credentials[$field])) {
                $this->logAndThrow($logging, 'sbiEpayClientInitializationError', 'keySecretError', $field);
            }
        }

        // Validate environment
        $environment = strtoupper($environment);
        $allowed = ['LIVE', 'SANDBOX'];
        if (!in_array($environment, $allowed, true)) {
            $this->logAndThrow($logging, 'sbiEpayClientInitializationError', 'environmentError');
        }

        //If 3rd Param is boolean Consider $responseType = 'JSON' as default
        if(is_bool($responseType)){
            $logging = $responseType;
            $responseType = 'JSON';
        }

        // Validate responseType
        $responseType = strtoupper($responseType);
        $allowedresponseType = ['JSON', 'STRING'];
        if (!in_array($responseType, $allowedresponseType, true)) {
            $this->logAndThrow($logging, 'sbiEpayClientInitializationError', 'responseTypeError');
        }

        
            // Prepare base URL
            $baseUrl = $environment === 'LIVE'
                ? Constants::get('baseUrl.LIVE_API_BASE_URL')
                : Constants::get('baseUrl.SANDBOX_API_BASE_URL');

            // HTTP client
            $this->client = new Client([
                'base_uri' => $baseUrl,
                'verify'   => false,
            ]);

            // Store config
            $this->config = [
                'apiKey'        => $credentials['apiKey'],
                'apiSecret'     => $credentials['apiSecret'],
                'encryptionKey' => $credentials['encryptionKey'],
                'responseType'  => $responseType,
                'logging'       => $logging,
                'base_uri'      => $baseUrl,
                'verify'        => false,
            ];

            // Initialize modules
            $this->customer = new Customer($this->client, $this->config);
            $this->order    = new Order($this->client, $this->config);
            $this->refund   = new Refund($this->client, $this->config);
            $this->crypto   = new Crypto($this->config);

        } catch (\Throwable $e) {
            Logger::log($logging, Constants::get('logTitles.sbiEpayClientInitializationError'), $e->getMessage());
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    private function logAndThrow(bool $logging, string $logTitleKey, string $errorKey, string $field = ''): void
    {
        $message = Constants::get("errors.$errorKey") . $field;
        Logger::log($logging, Constants::get("logTitles.$logTitleKey"), $message);
        throw new SBIEpayException(
                Constants::get('exception.IO_EXCEPTION_ERROR_CODE'),
                $message
            );
    }
}
