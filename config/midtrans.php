<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'is_production' => (bool) env('MIDTRANS_IS_PRODUCTION', false),
    'qris_acquirer' => env('MIDTRANS_QRIS_ACQUIRER', 'gopay'),
    'base_url' => env('MIDTRANS_IS_PRODUCTION', false)
        ? 'https://api.midtrans.com'
        : 'https://api.sandbox.midtrans.com',
];
