<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'sbiepay' => [
        'mid'            => env('SBIEPAY_MID', '1000003'),
        'api_key'        => env('SBIEPAY_API_KEY'),
        'api_secret'     => env('SBIEPAY_API_SECRET'),
        'encryption_key' => env('SBIEPAY_ENCRYPTION_KEY'),
        'env'            => env('SBIEPAY_ENV', 'SANDBOX'), // 'LIVE' or 'SANDBOX'
        'return_url'     => env('SBIEPAY_RETURN_URL'),
        'domain'         => env('SBIEPAY_DOMAIN', 'https://iphacon2027.com'),
    ],

    'festive_discount' => [
        'enabled'      => env('FESTIVE_DISCOUNT_ENABLED', true),
        'force_active' => env('FESTIVE_DISCOUNT_FORCE_ACTIVE', false),
        'percentage'   => (float) env('FESTIVE_DISCOUNT_PERCENT', 5.0),
        'start_date'   => env('FESTIVE_DISCOUNT_START', '2026-11-04 00:00:00'),
        'end_date'     => env('FESTIVE_DISCOUNT_END', '2026-11-08 23:59:59'),
        'timezone'     => env('FESTIVE_DISCOUNT_TIMEZONE', 'Asia/Kolkata'),
    ],


];
