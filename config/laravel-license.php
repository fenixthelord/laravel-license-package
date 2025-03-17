<?php

return [
    'mode' => env('LICENSE_MODE', 'client'), // القيم: server أو client
    'server_url' => env('LICENSE_SERVER_URL', 'https://license.yourdomain.com'),
    'validation_interval' => 86400, // 24 hours
    'offline_mode' => false,
    'encryption_key' => env('LICENSE_ENCRYPTION_KEY'),
    'key' => env('LICENSE_KEY'),
    'product_id' => env('LICENSE_PRODUCT_ID'),
    'api_key' => env('LICENSE_API_KEY'),
    'allow_offline' => env('LICENSE_ALLOW_OFFLINE', false),
    'check_frequency' => 'daily' // daily, weekly, monthly
];