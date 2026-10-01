<?php

namespace Sbiepay\modules;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleRetry\GuzzleRetryMiddleware;
use Sbiepay\exception\SBIEpayException;
use Sbiepay\utils\Constants;
use Sbiepay\utils\Logger;

class AccessToken
{
    private string $apiKey;
    private string $apiSecret;
    private array $config;

    public function __construct(array $config)
    {
        $this->config    = $config;
        $this->apiKey    = $config['apiKey'];
        $this->apiSecret = $config['apiSecret'];
    }

    /**
     * Retrieves the access token from the API.
     */
    public function getToken(): array
    {
        try {
            $client = $this->createClient();

            $response = $client->post(Constants::get('endpoints.accessToken'), [
                'headers' => $this->buildHeaders()
            ]);

            // decode response as associative array
            $decodedResponse = json_decode($response->getBody()->getContents(), true);

            if (empty($decodedResponse) || !is_array($decodedResponse)) {
                throw new SBIEpayException(Constants::get('exception.IO_EXCEPTION_ERROR_CODE'), Constants::get('exception.EXCEPTION_WHILE_CALLING_ACCESS_TOKEN_API'));
            }

            // extract status and errors in a safe, SDK-agnostic way
            $errorResponse = $decodedResponse['errors'] ?? null;
            $statusValue = $decodedResponse['status'] ?? $decodedResponse['Status'] ?? null;

            if ($response->getStatusCode() === 200) {
                if ((int)$statusValue === 1) {
                    return $decodedResponse;
                }

                // Log full response for debugging when status != 1
                Logger::log($this->config['logging'] ?? false, Constants::get('logTitles.accessTokenError'), $decodedResponse);

                throw new SBIEpayException(
                    Constants::get('exception.ERROR_RESPONSE_CODE'),
                    sprintf(
                        Constants::get('exception.INVALID_RESPONSE_ERROR_MSG'),
                        json_encode($errorResponse)
                    )
                );
            }

            throw new SBIEpayException(
                Constants::get('exception.INVALID_RESPONSE_ERROR_CODE'),
                sprintf(
                    Constants::get('exception.INVALID_RESPONSE_ERROR_MSG'),
                    json_encode($errorResponse)
                )
            );
        } catch (ConnectException $e) {
            $msg = 'Connection Error: Unable to connect to SBI ePay server (' . $e->getMessage() . ')';
            Logger::log($this->config['logging'], Constants::get('logTitles.networkError'), $msg);
            throw new SBIEpayException(Constants::get('exception.IO_EXCEPTION_ERROR_CODE'), $msg);
        } catch (RequestException $e) {
            $msg = $e->getMessage();
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = (string) $e->getResponse()->getBody();
                if ($statusCode === 403 || stripos($body, 'Access Restricted') !== false) {
                    $msg = "SBI ePay HTTP 403 (Access Restricted): Server/IP is not whitelisted by SBI ePay or credentials are unauthorized.";
                } else {
                    $msg = "SBI ePay HTTP {$statusCode}: " . substr(strip_tags($body), 0, 200);
                }
            }
            Logger::log($this->config['logging'], Constants::get('logTitles.requestError'), $msg);
            throw new SBIEpayException(Constants::get('exception.IO_EXCEPTION_ERROR_CODE'), $msg);
        } catch (SBIEpayException $e) {
            throw $e;
        }
    }

    /**
     * Build request headers.
     */
    private function buildHeaders(): array
    {
        return [
            'Content-Type'            => 'application/json',
            'Accept'                  => 'application/json',
            'Merchant-API-Key-Id'     => $this->apiKey,
            'Merchant-API-Key-Secret' => $this->apiSecret,
        ];
    }

    /**
     * Create a Guzzle client with retry middleware.
     */
    private function createClient(): Client
    {
        $handlerStack = HandlerStack::create();

        $handlerStack->push(GuzzleRetryMiddleware::factory([
            'max_retry_attempts' => 2,
            'retry_on_timeout'   => true,
            'on_retry_callback'  => function ($retryCount, $delay, $request, $options, $response = null, $exception = null) {
                $data = [
                    'attempt'   => $retryCount,
                    'delay_sec' => $delay,
                    'method'    => $request->getMethod(),
                    'uri'       => (string)$request->getUri(),
                    'exception' => $exception ? $exception->getMessage() : null,
                ];
                Logger::log($this->config['logging'] ?? false, Constants::get('logTitles.accessTokenError') . ' - retry', $data);
            },
        ]));

        return new Client([
            'base_uri' => $this->config['base_uri'],
            'handler'  => $handlerStack,
            'verify'   => $this->config['verify'] ?? true,
        ]);
    }
}
