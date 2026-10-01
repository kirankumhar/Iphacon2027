<?php

namespace Sbiepay\utils;

use Sbiepay\modules\AccessToken;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Sbiepay\exception\SBIEpayException;
use Sbiepay\utils\Logger;
use Sbiepay\utils\crypto\Encryption;
use Sbiepay\utils\crypto\Decryption;

class ApiServices
{
    private Client $client;
    protected string $encryptionKey;
    protected array $config;

    public function __construct(Client $client, array $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->encryptionKey = $config['encryptionKey'];
    }

    /**
     * Generates headers with access token
     */
    protected function updateInstance()
    {
        try {
            $accessTokenObj = new AccessToken($this->config);
            $response = $accessTokenObj->getToken();

            Logger::log($this->config['logging'], Constants::get("logTitles.accessTokenSuccess"), $response);

            return [
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $response['data'][0],
            ];
        } catch (SBIEpayException $e) {
            Logger::log($this->config['logging'], Constants::get("logTitles.accessTokenError"), $e->getMessage());
            throw $e;
        }
    }

    /**
     * Common HTTP request handler
     */
    private function apiRequest(string $method, string $endpoint, string $constant = '', array $payload = [])
    {

        try {
            $headers = $this->updateInstance();

            $options = ['headers' => $headers];
            if ($method === 'POST') {
                $options['json'] = Encryption::encryptPayload($this->encryptionKey, $payload);
            }

            $response = $this->client->request($method, $endpoint, $options);

            return $this->processEpayResponse($response, $constant);
            
        } catch (ConnectException $e) {
            Logger::log($this->config['logging'], Constants::get('logTitles.networkError'), $e->getMessage());
            throw new SBIEpayException(
                Constants::get('exception.IO_EXCEPTION_ERROR_CODE'),
                Constants::get('exception.IO_EXCEPTION_ERROR_MSG')
            );
            
        } catch (RequestException $e) {
            Logger::log($this->config['logging'], Constants::get('logTitles.requestError'), $e->getMessage());
            throw new SBIEpayException(
                Constants::get('exception.IO_EXCEPTION_ERROR_CODE'),
                Constants::get('exception.IO_EXCEPTION_ERROR_MSG')
            );
            
        } catch (SBIEpayException $e) {
            if ($constant) {
                Logger::log($this->config['logging'], Constants::get("logTitles.{$constant}Error"), $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * POST API call
     */
    protected function _post(string $endpoint, string $constant = '', array $payload = [])
    {
        return $this->apiRequest('POST', $endpoint, $constant, $payload);
    }

    /**
     * GET API call
     */
    protected function _get(string $endpoint, string $constant = '')
    {
        return $this->apiRequest('GET', $endpoint, $constant);
    }

    private function processEpayResponse($response, string $constant)
    {
        if ($response === null) {
            Logger::log($this->config['logging'], Constants::get("logTitles.{$constant}Error"));
            throw new SBIEpayException(
                Constants::get('exception.IO_EXCEPTION_ERROR_CODE'),
                Constants::get('exception.IO_EXCEPTION_ERROR_MSG')
            );
        }

        if (empty($response)) {
            Logger::log($this->config['logging'], Constants::get("logTitles.{$constant}Error"));
            throw new SBIEpayException(
                Constants::get("exception.{$constant}ERROR_CODE"),
                Constants::get("exception.{$constant}ERROR_MSG")
            );
        }

        try {
            // Get response body as string
            $responseBody = $response->getBody();

            // Decode JSON to associative array
            $decodedResponse = json_decode($responseBody, true);
            $errorResponse = $decodedResponse['errors'] ?? null;
            $statusValue = $decodedResponse['status'] ?? $decodedResponse['Status'] ?? null;

            if ($response->getStatusCode() === 200) {
                if ((int)$statusValue === 1) {
                    Logger::log($this->config['logging'], Constants::get("logTitles.{$constant}Success"), $decodedResponse);
                    return Decryption::decryptPayload($this->encryptionKey, $decodedResponse, $this->config);
                } else {
                    throw new SBIEpayException(
                        Constants::get('exception.ERROR_RESPONSE_CODE'),
                        sprintf(
                            Constants::get('exception.INVALID_RESPONSE_ERROR_MSG'),
                            json_encode($errorResponse)
                        )
                    );
                }
            } else {
                throw new SBIEpayException(
                    Constants::get('exception.INVALID_RESPONSE_ERROR_CODE'),
                    sprintf(
                        Constants::get('exception.INVALID_RESPONSE_ERROR_MSG'),
                        json_encode($errorResponse)
                    )
                );
            }
        } catch (\Exception $e) {
            Logger::log(
                $this->config['logging'],
                Constants::get("logTitles.{$constant}Error"),
                sprintf(
                    $responseBody,
                    ", error: " . $e->getMessage()
                )
            );

            throw $e;
        }
    }
}
