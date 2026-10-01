<?php

namespace Sbiepay\modules;

use Exception;
use Sbiepay\exception\SBIEpayException;
use Sbiepay\traits\ApiHelper;
use Sbiepay\utils\Constants;
use Sbiepay\utils\crypto\Aes;
use Sbiepay\utils\Logger;

class Crypto
{
    private string $encryptionKey;
    protected bool $logging;
    protected string $responseType;
    use ApiHelper;

    /**
     * Creates an instance of the Crypto.
     *
     * @param string $encryptionKey Encryption key for request/response encryption/decryption
     * @param bool   $logging       Enable/Disable API logs
     * @param string $responseType  STRING | JSON
     */
    public function __construct(array $config)
    {
        $this->encryptionKey = $config['encryptionKey'];
        $this->logging       = $config['logging'];
        $this->responseType  = $config['responseType'];
    }

    /**
     * Encrypt payload.
     *
     * @param mixed $payload
     * @return mixed
     * @throws Exception
     */
    public function encrypt($payload)
    {
        try {
            if (empty($payload) || $payload == "") {
                Logger::log($this->logging, Constants::get('logTitles.payloadEncryptError'), Constants::get('errors.payloadEncryptError'));
                throw new SBIEpayException(
                    Constants::get('exception.ENCRYPTION_ERROR_CODE'),
                    sprintf(Constants::get('exception.ENCRYPTION_DECRYPTION_ERROR_MSG'), Constants::get('errors.payloadEncryptError'))
                );
            }

            $dataToEncrypt = is_string($payload) ? $payload : json_encode($payload);

            $encryptedPayload = Aes::encrypt($this->encryptionKey, $dataToEncrypt);

            Logger::log($this->logging, Constants::get('logTitles.payloadEncryptSuccess'), $encryptedPayload);

            if (empty($encryptedPayload) || $encryptedPayload == "") {
                Logger::log($this->logging, Constants::get('logTitles.serviceFailed'), Constants::get('errors.serviceFailed'));
                throw new SBIEpayException(Constants::get('exception.ENCRYPTION_ERROR_CODE'), Constants::get('exception.ENCRYPTION_ERROR_MSG'));
            }

            return $encryptedPayload;
        } catch (\Throwable $e) {
            Logger::log(
                $this->logging,
                Constants::get('logTitles.serviceFailed'),
                $e->getMessage()
            );
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Decrypt payload.
     *
     * @param mixed $payload
     * @return mixed
     * @throws Exception
     */
    public function decrypt($payload)
    {

        try {
            if (empty($payload) || $payload == "") {
                Logger::log($this->logging, Constants::get('logTitles.payloadDecryptError'), Constants::get('errors.payloadDecryptError'));
                throw new SBIEpayException(
                    Constants::get('exception.ENCRYPTION_ERROR_CODE'),
                    sprintf(Constants::get('exception.ENCRYPTION_DECRYPTION_ERROR_MSG'), Constants::get('errors.payloadDecryptError'))
                );
            }

            $decryptedPayload = Aes::decrypt($this->encryptionKey, $payload);

            Logger::log(
                $this->logging,
                Constants::get('logTitles.payloadDecryptSuccess'),
                $decryptedPayload
            );

            if (empty($decryptedPayload) || $decryptedPayload == "" || $decryptedPayload == null) {
                Logger::log($this->logging, Constants::get('logTitles.serviceFailed'), Constants::get('errors.serviceFailed'));
                throw new SBIEpayException(Constants::get('exception.ENCRYPTION_ERROR_CODE'), Constants::get('errors.serviceFailed'));
            }

            return ($this->responseType === 'STRING')
                ? $decryptedPayload
                : json_decode($decryptedPayload, true);
        } catch (\Throwable $e) {
            Logger::log(
                $this->logging,
                Constants::get('logTitles.serviceFailed'),
                $e->getMessage()
            );
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Decode callback payload.
     *
     * @param mixed $payload
     * @return mixed
     * @throws Exception
     */
    public function decodeCallback($payload)
    {
        try {
            if (empty($payload) || $payload == "") {
                Logger::log($this->logging, Constants::get('logTitles.decodeCallbackError'), Constants::get('errors.decodeCallbackError'));
                throw new SBIEpayException(
                    Constants::get('exception.ENCRYPTION_ERROR_CODE'),
                    sprintf(Constants::get('exception.ENCRYPTION_DECRYPTION_ERROR_MSG'), Constants::get('errors.decodeCallbackError'))
                );
            }

            //Standard url and base64 decode 
            $uriDecoded = urldecode($payload);
            $base64Decoded = base64_decode($uriDecoded);
            $base64Decoded = str_replace('"', '', $base64Decoded);

            $base64Decoded = $this->customBase64Decode($payload);

            $decryptedPayload = Aes::decrypt($this->encryptionKey, $base64Decoded);

            Logger::log(
                $this->logging,
                Constants::get('logTitles.decodeCallbackSuccess'),
                $decryptedPayload
            );

            if (empty($decryptedPayload) || $decryptedPayload == "" || $decryptedPayload == null) {
                Logger::log($this->logging, Constants::get('logTitles.serviceFailed'), Constants::get('errors.serviceFailed'));
                throw new SBIEpayException(Constants::get('exception.ENCRYPTION_ERROR_CODE'), Constants::get('errors.serviceFailed'));
            }

            return ($this->responseType === 'STRING')
                ? $decryptedPayload
                : json_decode($decryptedPayload, true);
        } catch (\Throwable $e) {
            Logger::log(
                $this->logging,
                Constants::get('logTitles.serviceFailed'),
                $e->getMessage()
            );
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }
}
