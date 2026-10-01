<?php

namespace Sbiepay\utils\crypto;

use Sbiepay\exception\SBIEpayException;
use Sbiepay\utils\Constants;

class Encryption
{
    /**
     * Encrypt data payload.
     *
     * @param string $hVal
     * @param array $payload
     * @return array
     */
    public static function encryptPayload(string $hVal, array $payload): array
    {
        try {
            $encryptPayload = Aes::encrypt($hVal, json_encode($payload));

            $encryptedPayload = [
                "encryptedRequest" => $encryptPayload
            ];

            return $encryptedPayload;
        } catch (\Throwable $e) {
            throw new SBIEpayException(
                Constants::get('exception.ENCRYPTION_ERROR_CODE'),
                Constants::get('exception.ENCRYPTION_ERROR_MSG')
            );
        }
    }
}
