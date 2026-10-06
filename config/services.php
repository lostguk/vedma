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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'metaship' => [
        'api_key' => env('METASHIP_API_KEY'),
        'api_secret' => env('METASHIP_API_SECRET'),
        'shop_id' => env('METASHIP_SHOP_ID'),
        'warehouse_id' => env('METASHIP_WAREHOUSE_ID'),
    ],

    'tbank' => [
        'base_url' => env('TBANK_BASE_URL', 'https://securepay.tinkoff.ru/v2'),
        'terminal_key' => env('TBANK_TERMINAL_KEY'),
        'password' => env('TBANK_PASSWORD'),
        'return_url' => env('TBANK_RETURN_URL'),
        'fail_url' => env('TBANK_FAIL_URL'),
        'notification_url' => env('TBANK_NOTIFICATION_URL'),
        'currency' => env('TBANK_CURRENCY', 'RUB'),
        'verify_ssl' => filter_var(env('TBANK_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
        'fiscal' => [
            'enabled' => env('TBANK_FISCAL_ENABLED', false),
            'taxation' => env('TBANK_FISCAL_TAXATION', 'usn_income'),
            'tax' => env('TBANK_FISCAL_TAX', 'vat5'),
            'payment_method' => env('TBANK_FISCAL_PAYMENT_METHOD', 'full_payment'),
            'payment_object' => env('TBANK_FISCAL_PAYMENT_OBJECT', 'commodity'),
            'delivery_payment_object' => env('TBANK_FISCAL_DELIVERY_PAYMENT_OBJECT', 'service'),
            'delivery_name' => env('TBANK_FISCAL_DELIVERY_NAME', 'Доставка'),
        ],
    ],

    'dadata' => [
        'token' => env('DADATA_API_KEY'),
        'address_suggest_url' => env(
            'DADATA_ADDRESS_SUGGEST_URL',
            'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address'
        ),
    ],

];
