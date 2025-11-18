<?php

return [
    'order_statuses' => [
        'pending_confirmation' => 'Menunggu Konfirmasi',
        'processing' => 'Diproses',
        'ready_for_pickup' => 'Siap Diambil',
        'taken' => 'Diambil',
    ],
    'order_status_flow' => [
        'pending_confirmation',
        'processing',
        'ready_for_pickup',
        'taken',
    ],
    'payment_statuses' => [
        'pending' => 'Menunggu',
        'paid' => 'Lunas',
        'failed' => 'Gagal',
        'expired' => 'Kedaluwarsa',
        'skipped' => 'Tidak Diperlukan',
    ],
    'payment_methods' => [
        'cash' => 'Cash',
        'qris' => 'QRIS',
    ],
    'pickup_options' => [
        'none' => 'Tidak Perlu Pickup/Delivery',
        'pickup' => 'Pickup oleh Kurir',
        'delivery' => 'Antar ke Pelanggan',
    ],
    'inactive_statuses' => ['taken', 'cancelled'],
    'weight_discrepancy_threshold_percent' => 40,
    'qris_expiry_minutes' => 10,
    'max_active_orders_per_phone' => 3,
    'max_qris_regenerations_per_order_per_day' => 3,
];
