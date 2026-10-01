<?php

namespace Sbiepay\traits;

use Sbiepay\exception\SBIEpayException;
use Sbiepay\utils\Logger;
use Sbiepay\utils\Constants;

/**
 * Trait providing common API helper functions for validation
 */
trait ApiHelper
{
    /**
     * Validate the input data
     *
     * @param array $payload The data to validate
     * @param string $errorTitle Log title for error
     * @param string $errorKey Error message key
     * @throws SBIEpayException if validation fails
     */
    protected function validateInput(array $payload, string $constantKey, string $errorKey = ""): void
    {
        if (empty($payload)  || $payload === null) {
            Logger::log(
                $this->config['logging'],
                Constants::get("logTitles.{$constantKey}Error"),
                Constants::get($errorKey)
            );
            throw new SBIEpayException(
                Constants::get("exception.ERROR_RESPONSE_CODE"),
                sprintf(
                    Constants::get('exception.INVALID_RESPONSE_ERROR_MSG'),
                    implode("", ["'[{'errorCode':'2001','errorMessage':'", Constants::get($errorKey), "'}]'"])
                )
            );
        }
    }

    protected function toJsonIfString($value)
    {
        try {
            // Only try to decode if it's a string
            if (is_string($value)) {
                $decoded = json_decode($value, true);

                // Return decoded array if valid JSON
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
            }

            if (is_array($value)) {
                return $value;
            }

            throw new SBIEpayException(
                Constants::get('exception.IO_EXCEPTION_ERROR_CODE'),
                Constants::get('exception.REQUEST_EXCEPTION_ERROR_MSG')
            );
        } catch (SBIEpayException $e) {
            Logger::log($this->config['logging'], Constants::get('logTitles.requestError'), $e->getMessage());
            throw $e;
        }
    }

    protected function errorResponse(string $message)
    {
        return json_encode([
            'status' => 0,
            'errors' => [
                [
                    'errorCode' => '400',
                    'errorMessage' => $message ?? Constants::get('errors.apiError')
                ]

            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected function customBase64Decode(string $str): string
    {
        $base64 = str_replace(['-', '_'], ['+', '/'], $str);

        $padding = strlen($base64) % 4;
        if ($padding !== 0) {
            $base64 .= str_repeat("=", 4 - $padding);
        }

        return base64_decode($base64);
    }
}
