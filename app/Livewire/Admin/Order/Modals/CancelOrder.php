<?php

namespace App\Livewire\Admin\Order\Modals;

use App\Models\Order;
use App\Services\OrderEmailNotifier;
use Livewire\Component;

class CancelOrder extends Component
{
    public bool $show = false;
    public ?int $orderId = null;
    public array $summary = [];

    protected $listeners = ['open-cancel-modal' => 'open'];

    public function open(int $orderId): void
    {
        $order = Order::with('customer')->findOrFail($orderId);
        $this->orderId = $orderId;

        $statuses = config('orders.order_statuses', []);

        $this->summary = [
            'Kode' => $order->order_code,
            'Pelanggan' => $order->customer?->name ?? 'Tanpa Nama',
            'Status' => $statuses[$order->status] ?? ucfirst($order->status),
        ];

        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
        $this->reset(['orderId', 'summary']);
    }

    public function save(): void
    {
        if (!$this->orderId)
            return;

        $order = Order::findOrFail($this->orderId);
        $inactive = config('orders.inactive_statuses', []);

        if (in_array($order->status, array_merge($inactive, ['cancelled']), true)) {
            $this->dispatch('notify', message: 'Pesanan sudah tidak aktif.', type: 'error');
            $this->close();
            return;
        }

        $order->status = 'cancelled';
        $order->save();

        $order->appendActivity('admin', 'order_cancelled', []);
        app(OrderEmailNotifier::class)->sendStatusUpdated($order->fresh('customer', 'package'));

        $this->dispatch('order-updated', message: 'Pesanan dibatalkan.');
        $this->close();
    }

    public function render()
    {
        return view('livewire.admin.order.modals.cancel-order');
    }
}
