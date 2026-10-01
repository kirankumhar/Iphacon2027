<?php

namespace Sbiepay\modules;

use Sbiepay\utils\ApiServices;
use Sbiepay\traits\ApiHelper;
use GuzzleHttp\Client;
use Exception;
use Sbiepay\utils\Constants;
use Throwable;

class Order extends ApiServices
{
    use ApiHelper;

    public function __construct(Client $client, array $config)
    {
        parent::__construct($client, $config);
    }

    /**
     * Create a new order
     *
     * @param array $payload Order creation data
     * @throws Exception When API call fails
     */
    public function create($payload)
    {
        try {
            // $this->validateInput($payload, 'ORDER_CREATE_', 'errors.orderPayloadError');
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.orderCreate'), 'ORDER_CREATE_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Search orders
     *
     * @param array $payload Search criteria
     * @throws Exception When API call fails
     */
    public function search($payload)
    {
        try {
            // $this->validateInput($payload, 'ORDER_SEARCH_', 'errors.orderSearchError');
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.orderSearch'), 'ORDER_SEARCH_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Get settled Orders with pagination
     *
     * @param array $payload Search criteria
     * @param int $page Page number
     * @param int $size Page size
     * @throws Exception When API call fails
     */
    public function settledOrders($payload, int $page = 0, int $size = 50)
    {
        try {
            $payload = $this->toJsonIfString($payload);
            $payload['settlementStatus'] = Constants::get('status.SETTLED');
            return $this->_post(Constants::get('endpoints.mis') . "?page=$page&size=$size", 'ORDER_SETTLED_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Get transaction Orders with pagination
     *
     * @param array $payload Search criteria
     * @param int $page Page number
     * @param int $size Page size
     * @throws Exception When API call fails
     */
    public function transactionOrders($payload, int $page = 0, int $size = 50)
    {
        try {
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.mis') . "?page=$page&size=$size", 'ORDER_TRANSACTION_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

}
