<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use App\Services\OrderEmailNotifier;
use App\Services\QrisGenerator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use RuntimeException;

class Index extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $serviceTypeFilter = 'all';
    public $dateFrom;
    public $dateTo;
    public $actual_weight;
    public array $availableStatuses = [];

    public bool $showCreateModal = false;
    public bool $showExportModal = false;
    public bool $showAdvanceModal = false;
    public bool $showCancelModal = false;

    public ?int $advanceOrderId = null;
    public ?string $advanceCurrentStatus = null;
    public ?string $advanceNextStatus = null;
    public array $advanceSummary = [];

    public ?int $cancelOrderId = null;
    public array $cancelSummary = [];

    protected $listeners = [
        'order-created' => 'handleOrderCreated',
    ];

    public function mount(): void
    {
        $this->availableStatuses = config('orders.order_statuses', []);
    }

    public function render()
    {
        $orders = Order::with(['customer', 'package'])
            ->when($this->statusFilter !== 'all', fn ($query) => $query->where('status', $this->statusFilter))
            ->when($this->serviceTypeFilter !== 'all', fn ($query) => $query->where('service_type', $this->serviceTypeFilter))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->search, function ($query) {
                $keyword = '%'.$this->search.'%';
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('order_code', 'like', $keyword)
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery->where('name', 'like', $keyword))
                        ->orWhereHas('package', fn ($packageQuery) => $packageQuery->where('package_name', 'like', $keyword));
                });
            })
            ->latest()
            ->get();

        return view('livewire.admin.order.index', [
            'orders' => $orders,
            'statusLabels' => collect(['all' => 'Semua'])->merge($this->availableStatuses),
        ])->layout('layouts.admin', ['title' => 'Daftar Transaksi']);
    }

    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }
    public function handleOrderCreated(): void
    {
        $this->closeCreateModal();
        $this->dispatch('$refresh');
    }

    public function openExportModal(): void
    {
        $this->showExportModal = true;
    }

    public function closeExportModal(): void
    {
        $this->showExportModal = false;
    }

    public function confirmExport()
    {
        $params = array_filter([
            'status' => $this->statusFilter !== 'all' ? $this->statusFilter : null,
            'service_type' => $this->serviceTypeFilter !== 'all' ? $this->serviceTypeFilter : null,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ], fn ($value) => $value !== null);

        $this->closeExportModal();
        return redirect()->route('admin.reports.orders.export', $params);
    }

    public function openAdvanceModal(int $orderId): void
    {
        $order = Order::with(['customer', 'package'])->findOrFail($orderId);
        $nextStatus = $order->nextStatus();

        if (! $nextStatus) {
            session()->flash('error', 'Pesanan sudah berada di status akhir.');
            return;
        }

        $this->advanceOrderId = $orderId;
        $this->advanceCurrentStatus = $order->status;
        $this->advanceNextStatus = $nextStatus;
        $this->actual_weight = $order->actual_weight ?? $order->estimated_weight;
        $this->advanceSummary = [
            'Kode' => $order->order_code,
            'Pelanggan' => $order->customer?->name ?? 'Tanpa Nama',
            'Paket' => ($order->package?->package_name ?? '-') .' • '. ucfirst($order->service_type),
            'Berat' => number_format($this->actual_weight ?? 0, 1).' kg',
            'Total' => 'Rp '.number_format($order->total_price ?? 0, 0, ',', '.'),
            'Status berikutnya' => $this->availableStatuses[$nextStatus] ?? ucfirst($nextStatus),
        ];

        $this->showAdvanceModal = true;
    }

    public function closeAdvanceModal(): void
    {
        $this->showAdvanceModal = false;
        $this->advanceOrderId = null;
        $this->advanceCurrentStatus = null;
        $this->advanceNextStatus = null;
        $this->advanceSummary = [];
        $this->resetErrorBag('actual_weight');
    }

    public function confirmAdvanceWithWeight(): void
    {
        if (! $this->advanceOrderId) {
            return;
        }

        $this->validate([
            'actual_weight' => 'required|numeric|min:0.1',
        ]);

        $order = Order::with('package')->findOrFail($this->advanceOrderId);
        $order->actual_weight = $this->actual_weight;

        if (! $order->price_per_kg && $order->package) {
            $order->price_per_kg = $order->package->price_per_kg;
        }

        if ($order->price_per_kg) {
            $order->total_price = $this->actual_weight * $order->price_per_kg;
        }

        $order->save();

        $this->confirmAdvance();
    }

    public function confirmAdvance(): void
    {
        if (! $this->advanceOrderId) {
            return;
        }

        $order = Order::findOrFail($this->advanceOrderId);
        $nextStatus = $order->nextStatus();

        if (! $nextStatus) {
            session()->flash('error', 'Pesanan sudah berada di status akhir.');
            $this->closeAdvanceModal();
            return;
        }

        DB::transaction(function () use ($order, $nextStatus) {
            $order->status = $nextStatus;
            $order->save();

            $order->appendActivity('admin', 'status_progressed', [
                'status' => $nextStatus,
            ]);
        });

        $order->refresh();

        $qrisGenerated = false;

        try {
            $qrisGenerated = $this->maybeGenerateQrisPayment($order, $nextStatus);
        } catch (\Throwable $th) {
            report($th);
            session()->flash('error', 'QRIS pembayaran gagal dibuat. Coba beberapa saat lagi.');
        }

        $order->refresh();

        $additionalMessage = $qrisGenerated
            ? 'QRIS pembayaran siap dibagikan ke pelanggan dan dapat dilihat di halaman tracking.'
            : null;
        app(OrderEmailNotifier::class)->sendStatusUpdated($order->fresh('customer', 'package'), $additionalMessage);

        $message = 'Pesanan bergerak ke status '.($this->availableStatuses[$nextStatus] ?? ucfirst($nextStatus)).'.';
        if ($additionalMessage) {
            $message .= ' '.$additionalMessage;
        }

        session()->flash('message', $message);
        $this->closeAdvanceModal();
    }

    public function openCancelModal(int $orderId): void
    {
        $order = Order::with('customer')->findOrFail($orderId);
        $this->cancelOrderId = $orderId;
        $this->cancelSummary = [
            'Kode' => $order->order_code,
            'Pelanggan' => $order->customer?->name ?? 'Tanpa Nama',
            'Status' => $this->availableStatuses[$order->status] ?? ucfirst($order->status),
        ];
        $this->showCancelModal = true;
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
        $this->cancelOrderId = null;
        $this->cancelSummary = [];
    }

    public function confirmCancel(): void
    {
        if (! $this->cancelOrderId) {
            return;
        }

        $order = Order::findOrFail($this->cancelOrderId);
        $inactive = config('orders.inactive_statuses', []);
        if (in_array($order->status, array_merge($inactive, ['cancelled']), true)) {
            session()->flash('error', 'Pesanan sudah tidak aktif.');
            $this->closeCancelModal();
            return;
        }

        $order->status = 'cancelled';
        $order->save();

        $order->appendActivity('admin', 'order_cancelled', []);
        app(OrderEmailNotifier::class)->sendStatusUpdated($order->fresh('customer', 'package'));

        session()->flash('message', 'Pesanan dibatalkan.');
        $this->closeCancelModal();
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

        if (! $weight || ! $pricePerKg) {
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
}
