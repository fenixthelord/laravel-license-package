<?php

return [
    'mode' => env('LICENSE_MODE', 'client'), // القيم: server أو client
    'server_url' => env('LICENSE_SERVER_URL', 'https://license.yourdomain.com'),
    'validation_interval' => 86400, // 24 hours
    'offline_mode' => false,
    'encryption_key' => env('LICENSE_ENCRYPTION_KEY'),
];