# SBI Epay PHP SDK

Official PHP SDK for SBI ePay Transaction API.

---

## Requirements

- PHP >= 8.2.10
- Composer >= 2.8.12

---

## Installation

* Install the SDK using Composer

```bash
composer require sbiepay/epay-php-sdk
```

# Instantiate SBIEpay

### Usage
* import 'SBIEPayClient' class
```php
use Sbiepay\SBIEPayClient;

```


# To instantiate SBIEpayClient Properties Description
```php
To instantiate SBIEpayClient, with below params (Find Description Table below)
1. "apiKey", "apiSecret" and "encryptionKey" properties need to set in Object or Array 
2. environment
3. responseType
4. logging

1. credentials as an Object
$credentials = (object)[
    'apiKey' => "merchantApiKey",
    'apiSecret'  => "merchantApiSecret=",
    'encryptionKey' => "merchantApiEncryptionKey="
];

2. credentials as an Array
$credentials = [
    'apiKey' => "merchantApiKey",
    'apiSecret'  => "merchantApiSecret=",
    'encryptionKey' => "merchantApiEncryptionKey="
];
```

* To instantiate SBIEpayClient
```php
1. $sbiEpay = new SBIEPayClient($credentials);

2. $sbiEpay = new SBIEPayClient($credentials, 'LIVE', 'JSON', false);

3. $sbiEpay = new SBIEPayClient($credentials, 'LIVE');

4. $sbiEpay = new SBIEPayClient($credentials, $environment = 'LIVE', $responseType = 'STRING', $logging = false);
```


| Key/Property name | Optional    | Type            | Values             | Description                                                            |
|-------------------|-------------|-----------------|--------------------|------------------------------------------------------------------------|
| credentials       | No          | Object / Array  | credentials        | credentials as initialized above                                       |
| environment       | Yes         | string          | 'LIVE' / 'SANDBOX' | environment can be LIVE or SANDBOX default is LIVE                     |
| responseType      | Yes         | string          | 'JSON' / 'STRING'  | responseType can be JSON or STRING default is JSON                     |
| logging           | Yes         | bool            | true / false       | logging required can be enabled or disabled default is false(disabled) |



# API Listing

- [Customer](documents/Customer.md)
- [Order](documents/Order.md)
- [Refund](documents/Refund.md)
- [Crypto](documents/Crypto.md)
