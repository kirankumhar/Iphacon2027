<?php

namespace Sbiepay\utils\crypto;

use Sbiepay\exception\SBIEpayException;
use Sbiepay\utils\Constants;

class Decryption
{
    /**
     * Decrypt response payload and return JSON string similar to original implementation.
     *
     * @param string $hVal
     * @param array $response
     * @return string
     */
    public static function decryptPayload(string $hVal, array $response, array $config)
    {
        try {
            $decryptedResp = [
                "status" => $response['status'],
                "data" => []
            ];

            $payloadDetails = isset($response['data'][0]['encryptedResponse'])
                ? $response['data'][0]['encryptedResponse']
                : $response['data'][0];

            $decryptedPayload = Aes::decrypt($hVal, $payloadDetails);
            $decodedResponse = json_decode($decryptedPayload, true);

            if ($config["responseType"] == "JSON") {
                if (isset($response['count']) && isset($response['total'])) {
                    $final = [
                        'data'  => $decodedResponse,
                        'count' => $response['count'],
                        'total' => $response['total'],
                    ];
                    $decryptedResp["data"][] = $final;
                } else {
                    $decryptedResp["data"][] = $decodedResponse;
                }
            } else {

                $final = [
                    "data" => $decodedResponse
                ];

                if (isset($response['count'])) {
                    $final["count"] = $response['count'];
                }

                if (isset($response['total'])) {
                    $final["total"] = $response['total'];
                }

                $stringified = json_encode($final);

                $decryptedResp["data"][] = $stringified;
            }

            return json_encode($decryptedResp, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } catch (\Throwable $e) {
            throw new SBIEpayException(
                Constants::get('exception.DECRYPTION_ERROR_CODE'),
                Constants::get('exception.DECRYPTION_ERROR_MSG')
            );
        }
    }
}
