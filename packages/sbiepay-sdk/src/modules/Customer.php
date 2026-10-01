<?php

namespace Sbiepay\modules;

use Sbiepay\utils\ApiServices;
use Sbiepay\utils\Constants;
use GuzzleHttp\Client;
use Sbiepay\traits\ApiHelper;
use InvalidArgumentException;
use Exception;
use Throwable;

/**
 * Customer management class for handling customer-related API operations.
 */
class Customer extends ApiServices
{
    use ApiHelper;

    /**
     * Constructor for Customer class.
     * @param Client $client GuzzleHttp client instance
     * @param array $config Configuration array
     */
    public function __construct(Client $client, array $config)
    {
        parent::__construct($client, $config);
    }

    /** All API throws below Exception
     * @throws InvalidArgumentException When Argument is empty
     * @throws Exception When API call fails
     * */

    /**
     * Create a new customer.
     * @param array $payload Customer creation data
     */
    public function create($payload)
    {
        try {
            // $this->validateInput($payload, 'CUSTOMER_CREATE_', 'errors.customerPayloadError');
            $payload = $this->toJsonIfString($payload);
            return $this->_post(Constants::get('endpoints.customerCreate'), 'CUSTOMER_CREATE_', $payload);
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Fetch customer details by ID.
     * @param string $customerId Customer identifier
     */
    public function get(string $customerId)
    {
        try {
            // $this->validateInput(['id' => $customerId], 'CUSTOMER_FETCH_', 'errors.customerIdError');
            return $this->_get(Constants::get('endpoints.customer') . "/" . $customerId, 'CUSTOMER_FETCH_');
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Delete a customer.
     * @param string $customerId Customer identifier
     */
    public function delete(string $customerId)
    {
        try {
            // $this->validateInput(['id' => $customerId], 'CUSTOMER_UPDATE_', 'errors.customerIdError');
            return $this->updateStatus($customerId, Constants::get('status.DELETE'));
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Set customer status to inactive.
     * @param string $customerId Customer identifier
     */
    public function inactive(string $customerId)
    {
        try {
            // $this->validateInput(['id' => $customerId], 'CUSTOMER_UPDATE_', 'errors.customerIdError');
            return $this->updateStatus($customerId, Constants::get('status.INACTIVE'));
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Set customer status to active.
     * @param string $customerId Customer identifier
     */
    public function active(string $customerId)
    {
        try {
            // $this->validateInput(['id' => $customerId], 'CUSTOMER_UPDATE_', 'errors.customerIdError');
            return $this->updateStatus($customerId, Constants::get('status.ACTIVE'));
        } catch (Throwable $e) {
            throw new Exception($this->errorResponse($e->getMessage()));
        }
    }

    /**
     * Update customer status.
     * @param string $customerId Customer identifier
     * @param string $status New status value
     */
    private function updateStatus(string $customerId, string $status)
    {
        try {
            return $this->_post(Constants::get('endpoints.customer') . "/" . $customerId . "/" . $status, 'CUSTOMER_UPDATE_');
        } catch (Throwable $e) {
            throw $e;
        }
    }
}
