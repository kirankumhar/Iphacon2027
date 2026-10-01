<?php

namespace Sbiepay\utils;

class Constants
{
    private static array $constants = [
        "exception" => [
            "ENCRYPTION_ERROR_CODE" => 621,
            "DECRYPTION_ERROR_CODE" => 622,
            "JSON_PROCESSING_ERROR_CODE" => 623,
            "IO_EXCEPTION_ERROR_CODE" => 601,
            "INVALID_RESPONSE_ERROR_CODE" => 602,
            "ERROR_RESPONSE_CODE" => 603,
            "CUSTOMER_CREATE_ERROR_CODE" => 651,
            "CUSTOMER_FETCH_ERROR_CODE" => 651,
            "CUSTOMER_UPDATE_ERROR_CODE" => 651,
            "ORDER_CREATE_ERROR_CODE" => 652,
            "ORDER_SEARCH_ERROR_CODE" => 652,
            "REFUND_INITIATE_ERROR_CODE" => 653,
            "REFUND_SEARCH_ERROR_CODE" => 654,
            "TRANSACTION_STATUS_ERROR_CODE" => 655,
            "ENCRYPTION_ERROR_MSG" => "Encryption failed, Please check the encryption key and request.",
            "DECRYPTION_ERROR_MSG" => "Decryption failed, Please check the encryption key !",
            "ENCRYPTION_DECRYPTION_ERROR_MSG" => "Encryption Decryption Service Failed : %s",
            "IO_EXCEPTION_ERROR_MSG" => "InValid Response from Server",
            "REQUEST_EXCEPTION_ERROR_MSG" => "InValid Request Parameters",
            "INVALID_RESPONSE_ERROR_MSG" => "SBI EPay Business Validation Errors : %s",
            "CUSTOMER_CREATE_ERROR_MSG" => "Issue in Customer Creation.",
            "CUSTOMER_FETCH_ERROR_MSG" => "Issue in Customer Get.",
            "CUSTOMER_UPDATE_ERROR_MSG" => "Issue in Customer Update.",
            "ORDER_CREATE_ERROR_MSG" => "Issue in Order Creation.",
            "ORDER_SEARCH_ERROR_MSG" => "Issue in Order Search.",
            "REFUND_INITIATE_ERROR_MSG" => "Issue in Transaction Refund Initiation.",
            "REFUND_STATUS_ERROR_MSG" => "Issue in getting Refund Search.",
            "EXCEPTION_WHILE_CALLING_ACCESS_TOKEN_API" => "exception while calling access token api",
            "ORDER_SETTLED_ERROR_MSG" => "Issue in getting Settled Orders",
            "ORDER_TRANSACTION_ERROR_MSG" => "Issue in getting Transaction Orders",
        ],
        'errors' => [
            "keySecretError" => "Missing required credentials: ",
            "environmentError" => "SDK environment should be LIVE or SANDBOX",
            "responseTypeError" => "SDK responseType should be JSON or STRING",
            "loggingError" => "logging should be boolean (true/false) ",
            "apiError" => "Something went wrong!",
            "urlError" => "API url is required",
            "customerPayloadError" => "Customer payload is required",
            "customerIdError" => "CustomerID is required",
            "orderPayloadError" => "Order payload is required",
            "orderSearchError" => "Order search payload is required",
            "refundPayloadError" => "Refund payload is required",
            "refundSearchError" => "Refund search payload is required",
            "payloadEncryptError" => "Encrypt payload is required",
            "payloadDecryptError" => "Decrypt payload is required",
            "decodeCallbackError" => "Callback payload is required",
            "serviceFailed" => "Encryption Decryption Service Failed",
        ],
        'logTitles' => [
            "sbiEpayClientInitializationError" => "SBIEpayClient initialization error",
            "networkError" => "Network Error",
            "requestError" => "Request Error",
            "accessTokenSuccess" => "Get token success",
            "accessTokenError" => "Get token error",
            "CUSTOMER_CREATE_Success" => "Customer create success",
            "CUSTOMER_CREATE_Error" => "Customer create error",
            "CUSTOMER_FETCH_Success" => "Customer fetch success",
            "CUSTOMER_FETCH_Error" => "Customer fetch error",
            "CUSTOMER_UPDATE_Success" => "Customer update success",
            "CUSTOMER_UPDATE_Error" => "Customer update error",
            "ORDER_CREATE_Success" => "Order create success",
            "ORDER_CREATE_Error" => "Order create error",
            "ORDER_SEARCH_Success" => "Order search success",
            "ORDER_SEARCH_Error" => "Order search error",
            "REFUND_INITIATE_Success" => "Refund book success",
            "REFUND_INITIATE_Error" => "Refund book error",
            "REFUND_SEARCH_Success" => "Refund search success",
            "REFUND_SEARCH_Error" => "Refund search error",
            "payloadEncryptSuccess" => "Payload encrypt success",
            "payloadEncryptError" => "Payload encrypt error",
            "serviceFailed" => "Encryption Decryption Service Error",
            "payloadDecryptSuccess" => "Payload decrypt success",
            "payloadDecryptError" => "Payload decrypt error",
            "decodeCallbackSuccess" => "Decode callback success",
            "decodeCallbackError" => "Decode callback error",
            "ORDER_SETTLED_Success" => "Order settled success",
            "ORDER_SETTLED_Error" => "Order settled error",
            "ORDER_TRANSACTION_Success" => "Transaction Orders error",
            "ORDER_TRANSACTION_Error" => "Transaction Orders error",
        ],
        'baseUrl' => [
            "LIVE_API_BASE_URL" => "https://sbiepay.sbi.bank.in/api/transaction/v1/",
            "SANDBOX_API_BASE_URL" => "https://integration.sbiepay.sbiuat.bank.in/api/transaction/v1/",
        ],

        'status' => [
            'ACTIVE' => "ACTIVE",
            'INACTIVE' => "INACTIVE",
            'DELETE' => "DELETE",
            'SETTLED' => 'SETTLED'
        ],

        "endpoints" => [
            "accessToken" => "token/access",
            "customerCreate" => "customer/create",
            "customer" => "customer",
            "orderCreate" => "order/create",
            "orderSearch" => "order/status",
            "refundBook" => "refund/book",
            "refundSearch" => "refund/search",
            "mis" => "mis/report"
        ]
    ];

    /**
     * Example: Config::get("errors.orderCreateError")
     */
    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = self::$constants;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }
}
