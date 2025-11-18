<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'package_id',
        'estimated_weight',
        'actual_weight',
        'price_per_kg',
        'service_type',
        'notes',
        'status',
        'total_price',
        'payment_method',
        'payment_status',
        'queue_position',
        'estimated_completion',
        'pickup_or_delivery',
        'delivery_fee',
        'admin_id',
        'activity_log',
    ];

    protected $casts = [
        'estimated_weight' => 'decimal:2',
        'actual_weight' => 'decimal:2',
        'price_per_kg' => 'decimal:2',
        'total_price' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'estimated_completion' => 'datetime',
        'activity_log' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return config('orders.order_statuses')[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return config('orders.payment_statuses')[$this->payment_status] ?? ucfirst($this->payment_status);
    }

    public function appendActivity(string $actor, string $action, array $meta = []): void
    {
        $log = $this->activity_log ?? [];
        $log[] = [
            'actor' => $actor,
            'action' => $action,
            'meta' => $meta,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->activity_log = $log;
        $this->save();
    }

    public function nextStatus(): ?string
    {
        $flow = static::statusFlow();
        $index = array_search($this->status, $flow, true);

        return $index === false ? null : ($flow[$index + 1] ?? null);
    }

    public static function statusFlow(): array
    {
        return config('orders.order_status_flow', []);
    }
}
