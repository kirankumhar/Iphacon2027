<?php

namespace Sbiepay\modules;

use Sbiepay\utils\ApiServices;
use Sbiepay\traits\ApiHelper;
use GuzzleHttp\Client;
use Exception;
use Sbiepay\utils\Constants;
use Throwable;

class Refund extends ApiServices
{
    use ApiHelper;

    public function __construct(Client $client, array $config)
    {
        parent::__construct($client, $config);
    }

    /**
     * Book a new refund
     *
     * @param array $payload Refund booking data
     * @throws Exception When API call fails
     */
    public function book($payload)
    {
        try {
            // $this->validateInput($payload, 'REFUND_INITIATE_', 'errors.refundPayloadError');
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.refundBook'), 'REFUND_INITIATE_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Search refunds with pagination
     *
     * @param array $payload Search criteria
     * @param int $page Page number
     * @param int $size Page size
     * @throws Exception When API call fails
     */
    public function search($payload, int $page = 0, int $size = 50)
    {
        try {
            // $this->validateInput($payload, 'REFUND_SEARCH_', 'errors.refundSearchError');
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.refundSearch') . "?page=$page&size=$size", 'REFUND_SEARCH_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }
}
