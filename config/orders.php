<?php

return [
    'order_statuses' => [
        'order_created' => 'Order Dibuat',
        'accepted' => 'Diterima',
        'weight_set' => 'Berat Ditetapkan',
        'waiting_payment' => 'Menunggu Pembayaran',
        'paid' => 'Pembayaran Diterima',
        'washing' => 'Dicuci',
        'drying' => 'Dikeringkan',
        'ironing' => 'Disetrika',
        'packing' => 'Dikemas',
        'ready_for_pickup' => 'Siap Diambil',
        'out_for_delivery' => 'Sedang Diantar',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
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
    'weight_discrepancy_threshold_percent' => 40,
    'qris_expiry_minutes' => 30,
    'max_active_orders_per_phone' => 3,
    'max_qris_regenerations_per_order_per_day' => 3,
];
