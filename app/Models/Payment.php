<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'status',
        'qris_url',
        'qris_image_url',
        'qris_payload',
        'midtrans_transaction_id',
        'expiry_time',
        'regeneration_count',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expiry_time' => 'datetime',
        'meta' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
