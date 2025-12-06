<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class TrackOrder extends Component
{
    public $order_code;
    public $order;
    public $errorMessage;

    public function mount(): void
    {
        $code = request()->query('code');

        if ($code) {
            $this->order_code = $code;
            $this->track();
        }
    }

    public function track()
    {
        $this->validate([
            'order_code' => 'required|string',
        ]);

        $this->errorMessage = null;
        $this->order = $this->queryOrder($this->order_code);

        if (!$this->order) {
            $this->errorMessage = 'Kode tidak ditemukan, silakan cek kembali.';
            return;
        }

        $this->checkAndRegenerateQris();
    }

    public function refreshStatus(): void
    {
        if (!$this->order_code || !$this->order) {
            return;
        }

        $this->order = $this->queryOrder($this->order_code);
        $this->checkAndRegenerateQris();
    }

    protected function queryOrder(string $code): ?Order
    {
        return Order::where('order_code', $code)
            ->with(['customer', 'package', 'payments' => fn($query) => $query->latest()])
            ->first();
    }

    protected function checkAndRegenerateQris(): void
    {
        if (!$this->order)
            return;

        // Only for Unpaid QRIS that is NOT pending confirmation (i.e. already processing/price set)
        if ($this->order->payment_method !== 'qris')
            return;
        if ($this->order->payment_status === 'paid')
            return;
        if ($this->order->status === 'pending_confirmation')
            return; // Price might not be final

        $latestPayment = $this->order->payments->firstWhere('status', 'pending');

        $needsRegeneration = false;

        // Condition 1: No pending payment exists at all
        if (!$latestPayment) {
            $needsRegeneration = true;
        }
        // Condition 2: Pending payment is expired
        elseif ($latestPayment->expiry_time && now()->greaterThan($latestPayment->expiry_time)) {
            $needsRegeneration = true;
            // Mark old one as expired
            $latestPayment->update(['status' => 'expired']);
        }

        if ($needsRegeneration) {
            $this->regenerateQris($this->order);
            // Refresh order relationship to show new payment immediately
            $this->order->load(['payments' => fn($query) => $query->latest()]);
        }
    }

    protected function regenerateQris(Order $order): void
    {
        $weight = $order->actual_weight ?? $order->estimated_weight;
        $pricePerKg = $order->price_per_kg ?? $order->package?->price_per_kg;
        $deliveryFee = $order->delivery_fee ?? 0;

        if (!$weight || !$pricePerKg)
            return;

        $amount = (float) (($weight * $pricePerKg) + $deliveryFee);

        try {
            $payload = app(\App\Services\QrisGenerator::class)->generate($amount, $order->order_code);

            $order->payments()->create([
                'method' => 'qris',
                'amount' => $amount,
                'status' => 'pending',
                'qris_url' => $payload['qris_url'] ?? null,
                'qris_image_url' => $payload['qris_image_url'] ?? null,
                'qris_payload' => $payload['payload'] ?? null,
                'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                'expiry_time' => $payload['expiry'] ?? null,
            ]);

            // Log silent activity or just skip to avoid cluttering admin log? 
            // Maybe skip logging for auto-regen to reduce DB noise, or log system action.
            $order->appendActivity('system', 'qris_regenerated_auto', ['amount' => $amount]);

        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function render()
    {
        return view('livewire.track-order')
            ->layout('layouts.site', ['title' => 'Tracking Pesanan']);
    }
}
