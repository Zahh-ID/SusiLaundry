<?php

namespace App\Livewire\Admin\Order\Modals;

use App\Models\Order;
use App\Services\OrderEmailNotifier;
use App\Services\QrisGenerator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use RuntimeException;

class AdvanceOrder extends Component
{
    public bool $show = false;
    public ?int $orderId = null;
    public ?string $currentStatus = null;
    public ?string $nextStatus = null;
    public $actual_weight;
    public array $summary = [];

    // Payment Confirmation
    public bool $markAsPaid = false;
    public bool $showPaymentAlert = false;

    // QRIS Logic
    public array $qrisData = [];

    protected $listeners = ['open-advance-modal' => 'open'];

    public function open(int $orderId): void
    {
        $order = Order::with(['customer', 'package', 'payments'])->findOrFail($orderId);
        $nextStatus = $order->nextStatus();

        if (!$nextStatus) {
            $this->dispatch('notify', message: 'Pesanan sudah berada di status akhir.', type: 'error');
            return;
        }

        $this->orderId = $orderId;
        $this->currentStatus = $order->status;
        $this->nextStatus = $nextStatus;
        $this->actual_weight = $order->actual_weight ?? $order->estimated_weight;
        $this->markAsPaid = false;
        $this->showPaymentAlert = false;
        $this->qrisData = [];

        // Load pending QRIS data if exists
        if ($order->payment_method === 'qris' && $order->payment_status !== 'paid') {
            $latestPayment = $order->payments()->where('status', 'pending')->latest()->first();
            if ($latestPayment) {
                $this->qrisData = [
                    'qris_image_url' => $latestPayment->qris_image_url,
                    'expiry_time' => $latestPayment->expiry_time,
                    'amount' => $latestPayment->amount,
                ];
            }
        }

        $statuses = config('orders.order_statuses', []);

        $this->summary = [
            'Kode' => $order->order_code,
            'Pelanggan' => $order->customer?->name ?? 'Tanpa Nama',
            'Paket' => ($order->package?->package_name ?? '-') . ' • ' . ucfirst($order->service_type),
            'Berat' => number_format($this->actual_weight ?? 0, 1) . ' kg',
            'Total' => 'Rp ' . number_format($order->total_price ?? 0, 0, ',', '.'),
            'Status berikutnya' => $statuses[$nextStatus] ?? ucfirst($nextStatus),
            'Pembayaran' => ucfirst($order->payment_method) . ' (' . ucfirst($order->payment_status) . ')',
        ];

        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->reset(['orderId', 'currentStatus', 'nextStatus', 'summary', 'actual_weight', 'markAsPaid', 'showPaymentAlert', 'qrisData']);
        $this->resetErrorBag();
    }

    public function saveWithWeight(): void
    {
        if (!$this->orderId)
            return;

        $this->validate([
            'actual_weight' => 'required|numeric|min:0.1',
        ]);

        $order = Order::with('package')->findOrFail($this->orderId);
        $order->actual_weight = $this->actual_weight;

        if (!$order->price_per_kg && $order->package) {
            $order->price_per_kg = $order->package->price_per_kg;
        }

        if ($order->price_per_kg) {
            $order->total_price = $this->actual_weight * $order->price_per_kg;
        }

        if ($this->markAsPaid && $order->payment_method === 'cash') {
            $this->processPayment($order);
        }

        $order->save();
        $this->save();
    }

    public function confirmPaymentAndSave(): void
    {
        if (!$this->orderId)
            return;
        $order = Order::findOrFail($this->orderId);
        $this->processPayment($order);
        $this->save(force: true);
    }

    public function skipPaymentAndSave(): void
    {
        // Enforce strict check: If status is 'taken', we cannot proceed without payment.
        if ($this->nextStatus === 'taken') {
            $this->dispatch('notify', message: 'Status tidak berubah. Pembayaran wajib lunas untuk pengambilan.', type: 'error');
            $this->close();
            return;
        }

        $this->save(force: true);
    }

    protected function processPayment(Order $order): void
    {
        $order->payment_status = 'paid';
        $order->payments()->where('status', '!=', 'paid')->update(['status' => 'paid']); // Update existing payments
        // If no payment record exists, create one? (Assuming created at order creation)
        $order->save();
        $order->appendActivity('admin', 'payment_confirmed_manually', ['method' => 'cash']);
    }

    public function regenerateQris(): void
    {
        if (!$this->orderId)
            return;

        $order = Order::with('package')->findOrFail($this->orderId);

        // Expire existing pending payments
        $order->payments()->where('status', 'pending')->update(['status' => 'expired']);

        $weight = $order->actual_weight ?? $order->estimated_weight;
        $pricePerKg = $order->price_per_kg ?? $order->package?->price_per_kg;
        $deliveryFee = $order->delivery_fee ?? 0;

        if (!$weight || !$pricePerKg) {
            $this->dispatch('notify', message: 'Data berat/harga tidak valid.', type: 'error');
            return;
        }

        $amount = (float) (($weight * $pricePerKg) + $deliveryFee);

        try {
            $payload = app(QrisGenerator::class)->generate($amount, $order->order_code);

            $payment = $order->payments()->create([
                'method' => 'qris',
                'amount' => $amount,
                'status' => 'pending',
                'qris_url' => $payload['qris_url'] ?? null,
                'qris_image_url' => $payload['qris_image_url'] ?? null,
                'qris_payload' => $payload['payload'] ?? null,
                'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                'expiry_time' => $payload['expiry'] ?? null,
            ]);

            $this->qrisData = [
                'qris_image_url' => $payment->qris_image_url,
                'expiry_time' => $payment->expiry_time,
                'amount' => $payment->amount,
            ];

            $order->appendActivity('admin', 'qris_regenerated', ['amount' => $amount]);
            $this->showPaymentAlert = true; // Ensure alert stays open with new data

        } catch (\Throwable $th) {
            report($th);
            $this->dispatch('notify', message: 'Gagal generate QRIS baru.', type: 'error');
        }
    }

    public function save(bool $force = false): void
    {
        if (!$this->orderId)
            return;

        $order = Order::findOrFail($this->orderId);
        $nextStatus = $order->nextStatus();

        if (!$nextStatus) {
            $this->dispatch('notify', message: 'Pesanan sudah berada di status akhir.', type: 'error');
            $this->close();
            return;
        }

        // Check for Unpaid Orders (Cash OR QRIS)
        // If unpaid and not forced, show the alert/popup
        if (!$force && $order->payment_status !== 'paid') {
            // Apply for Cash OR QRIS
            if ($order->payment_method === 'cash' || $order->payment_method === 'qris') {
                $this->showPaymentAlert = true;
                return;
            }
        }

        if ($nextStatus === 'taken' && $order->payment_status !== 'paid') {
            $this->dispatch('notify', message: 'Gagal: Pesanan belum lunas tidak dapat diambil!', type: 'error');
            $this->close();
            return;
        }

        DB::transaction(function () use ($order, $nextStatus) {
            $order->status = $nextStatus;
            $order->save();

            $order->appendActivity('admin', 'status_progressed', [
                'status' => $nextStatus,
            ]);
        });

        // ... rest of logic

        $order->refresh();
        $qrisGenerated = false;

        try {
            $qrisGenerated = $this->maybeGenerateQrisPayment($order, $nextStatus);
        } catch (\Throwable $th) {
            report($th);
            $this->dispatch('notify', message: 'QRIS pembayaran gagal dibuat.', type: 'error');
        }

        $order->refresh();

        $additionalMessage = $qrisGenerated
            ? 'QRIS pembayaran siap dibagikan ke pelanggan.'
            : null;

        app(OrderEmailNotifier::class)->sendStatusUpdated($order->fresh('customer', 'package'), $additionalMessage);

        $statuses = config('orders.order_statuses', []);
        $statusLabel = $statuses[$nextStatus] ?? ucfirst($nextStatus);

        $message = "Pesanan bergerak ke status {$statusLabel}.";
        if ($additionalMessage) {
            $message .= ' ' . $additionalMessage;
        }

        if ($order->payment_status === 'paid' && $order->wasChanged('payment_status')) {
            $message .= ' Pembayaran telah dikonfirmasi.';
        }

        $this->dispatch('order-updated', message: $message);
        $this->close();
    }

    protected function maybeGenerateQrisPayment(Order $order, string $nextStatus): bool
    {
        if ($order->payment_method !== 'qris' || $order->payment_status === 'paid') {
            return false;
        }

        if ($nextStatus !== 'processing') {
            return false;
        }

        if ($order->payments()->where('status', 'pending')->exists()) {
            return false;
        }

        $order->loadMissing('package');

        $weight = $order->actual_weight ?? $order->estimated_weight;
        $pricePerKg = $order->price_per_kg ?? $order->package?->price_per_kg;

        if (!$weight || !$pricePerKg) {
            throw new RuntimeException('Berat aktual atau harga paket belum ditetapkan.');
        }

        $deliveryFee = $order->delivery_fee ?? 0;
        $amount = (float) (($weight * $pricePerKg) + $deliveryFee);

        $payload = app(QrisGenerator::class)->generate($amount, $order->order_code);

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

        $order->payment_status = 'pending';
        $order->save();

        $order->appendActivity('system', 'qris_generated', [
            'amount' => $amount,
        ]);

        return true;
    }

    public function render()
    {
        return view('livewire.admin.order.modals.advance-order');
    }
}
