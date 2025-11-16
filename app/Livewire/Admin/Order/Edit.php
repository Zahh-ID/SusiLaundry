<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use App\Services\QrisGenerator;
use App\Services\WhatsappNotifier;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public Order $order;
    public $status;
    public $actual_weight;
    public $price_per_kg;
    public $total_price;
    public $payment_method;
    public $payment_status;
    public $pickup_or_delivery;
    public $delivery_fee;
    public $estimated_completion;
    public array $availableStatuses = [];
    public array $paymentMethods = [];
    public array $paymentStatuses = [];
    public array $pickupOptions = [];
    public $latestPayment;

    public function mount(Order $order): void
    {
        $this->order = $order->load('payments');
        $this->status = $order->status;
        $this->actual_weight = $order->actual_weight;
        $this->price_per_kg = $order->price_per_kg ?? $order->package->price_per_kg;
        $this->total_price = $order->total_price;
        $this->payment_method = $order->payment_method;
        $this->payment_status = $order->payment_status;
        $this->pickup_or_delivery = $order->pickup_or_delivery;
        $this->delivery_fee = $order->delivery_fee;
        $this->estimated_completion = $order->estimated_completion?->format('Y-m-d\TH:i');

        $this->availableStatuses = config('orders.order_statuses');
        $this->paymentMethods = config('orders.payment_methods');
        $this->paymentStatuses = config('orders.payment_statuses');
        $this->pickupOptions = config('orders.pickup_options');
        $this->latestPayment = $order->payments()->latest()->first();
    }

    public function updatedActualWeight(): void
    {
        if ($this->actual_weight && $this->price_per_kg) {
            $this->total_price = $this->actual_weight * $this->price_per_kg;
        }
    }

    public function updatedPricePerKg(): void
    {
        $this->updatedActualWeight();
    }

    public function update()
    {
        $this->validate([
            'status' => 'required|string',
            'actual_weight' => 'nullable|numeric|min:0',
            'price_per_kg' => 'nullable|numeric|min:0',
            'total_price' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,qris',
            'payment_status' => 'required|string',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'delivery_fee' => 'nullable|numeric|min:0',
            'estimated_completion' => 'nullable|date',
        ]);

        $total = $this->total_price;
        if (!$total && $this->actual_weight && $this->price_per_kg) {
            $total = $this->actual_weight * $this->price_per_kg;
        }

        $originalStatus = $this->order->status;
        $originalPaymentStatus = $this->order->payment_status;

        $this->order->update([
            'status' => $this->status,
            'actual_weight' => $this->actual_weight,
            'price_per_kg' => $this->price_per_kg,
            'total_price' => $total,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'pickup_or_delivery' => $this->pickup_or_delivery,
            'delivery_fee' => $this->delivery_fee,
            'estimated_completion' => $this->estimated_completion,
            'admin_id' => Auth::id(),
        ]);

        $this->order->appendActivity('admin', 'order_updated', [
            'status' => $this->status,
            'payment_status' => $this->payment_status,
        ]);

        $notifier = app(WhatsappNotifier::class);
        if ($originalStatus !== $this->status || $originalPaymentStatus !== $this->payment_status) {
            $notifier->notifyStatusUpdated($this->order->fresh('customer', 'package'));
        }

        session()->flash('message', 'Pesanan berhasil diperbarui.');
        return redirect()->route('admin.orders.index');
    }

    public function markPaymentPaid(): void
    {
        $payment = $this->order->payments()->latest()->first();
        if ($payment) {
            $payment->update(['status' => 'paid']);
        }
        $this->order->update(['payment_status' => 'paid']);
        $this->order->appendActivity('admin', 'payment_marked_paid', []);
        $this->payment_status = 'paid';
        $this->latestPayment = $payment?->refresh();
        session()->flash('message', 'Pembayaran ditandai lunas.');
    }

    public function regenerateQris(QrisGenerator $generator): void
    {
        if ($this->order->payment_method !== 'qris') {
            return;
        }

        $todayRegens = $this->order->payments()
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($todayRegens >= config('orders.max_qris_regenerations_per_order_per_day')) {
            session()->flash('error', 'Batas regenerasi QRIS hari ini tercapai.');
            return;
        }

        $amount = ($this->order->total_price ?? 0) + ($this->order->delivery_fee ?? 0);
        try {
            $payload = $generator->generate($amount, $this->order->order_code);
        } catch (\Throwable $th) {
            report($th);
            session()->flash('error', 'Regenerasi QRIS gagal. Coba beberapa saat lagi.');
            return;
        }
        $count = ($this->order->payments()->latest()->value('regeneration_count') ?? 0) + 1;

        $payment = $this->order->payments()->create([
            'method' => 'qris',
            'amount' => $amount,
            'status' => 'pending',
            'qris_url' => $payload['qris_url'],
            'qris_image_url' => $payload['qris_image_url'],
            'qris_payload' => $payload['payload'],
            'midtrans_transaction_id' => $payload['transaction_id'],
            'expiry_time' => $payload['expiry'],
            'regeneration_count' => $count,
        ]);

        $this->order->update(['payment_status' => 'pending']);
        $this->order->appendActivity('admin', 'qris_regenerated', [
            'reference' => $payload['transaction_id'],
        ]);

        $this->latestPayment = $payment;
        $this->payment_status = 'pending';
        session()->flash('message', 'QRIS baru berhasil dibuat.');
    }

    public function render()
    {
        return view('livewire.admin.order.edit')
            ->layout('layouts.admin', ['title' => 'Perbarui Pesanan']);
    }
}
