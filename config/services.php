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

    'alfabank' => [
        'base_url' => env('ALFABANK_BASE_URL', 'https://payment.alfabank.ru'),
        'username' => env('ALFABANK_USERNAME'),
        'password' => env('ALFABANK_PASSWORD'),
        'return_url' => env('ALFABANK_RETURN_URL'),
        'fail_url' => env('ALFABANK_FAIL_URL'),
        'language' => env('ALFABANK_LANGUAGE', 'ru'),
        'currency' => env('ALFABANK_CURRENCY', 'RUB'),
        'fiscal' => [
            'enabled' => env('ALFABANK_FISCAL_ENABLED', false),
            'tax_system' => env('ALFABANK_FISCAL_TAX_SYSTEM', 1),
            'default_tax_type' => env('ALFABANK_FISCAL_TAX_TYPE', 10),
            'payment_method' => env('ALFABANK_FISCAL_PAYMENT_METHOD', 4),
            'payment_object' => env('ALFABANK_FISCAL_PAYMENT_OBJECT', 1),
            'delivery_payment_object' => env('ALFABANK_FISCAL_DELIVERY_PAYMENT_OBJECT', 4),
            'delivery_name' => env('ALFABANK_FISCAL_DELIVERY_NAME', 'Доставка'),
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
