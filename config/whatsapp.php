<?php

return [
    'enabled' => env('WHATSAPP_ENABLED', false),
    'api_version' => env('WHATSAPP_API_VERSION', 'v20.0'),
    'from_phone_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
    'access_token' => env('WHATSAPP_ACCESS_TOKEN'),
    'fallback_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '62'),
];
