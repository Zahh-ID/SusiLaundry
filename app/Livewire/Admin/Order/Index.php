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
    use \Livewire\WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $serviceTypeFilter = 'all';
    public $dateFrom;
    public $dateTo;
    public array $availableStatuses = [];

    public function openCreateModal(): void
    {
        $this->dispatch('open-create-modal');
    }

    public function handleOrderCreated($message = null): void
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->dispatch('$refresh');
    }

    public function handleOrderUpdated($message = null): void
    {
        if ($message) {
            session()->flash('message', $message);
        }
        $this->dispatch('$refresh');
    }

    public function handleNotify($message, $type = 'success'): void
    {
        session()->flash($type === 'error' ? 'error' : 'message', $message);
    }

    public function openExportModal(): void
    {
        $filters = [
            'status' => $this->statusFilter,
            'service_type' => $this->serviceTypeFilter,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
        ];
        $this->dispatch('open-export-modal', filters: $filters);
    }

    public function openAdvanceModal(int $orderId): void
    {
        $this->dispatch('open-advance-modal', orderId: $orderId);
    }

    public function openCancelModal(int $orderId): void
    {
        $this->dispatch('open-cancel-modal', orderId: $orderId);
    }

    public function render()
    {
        $query = Order::with(['customer', 'package', 'payments']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('package', function ($q) {
                        $q->where('package_name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->serviceTypeFilter !== 'all') {
            $query->where('service_type', $this->serviceTypeFilter);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $orders = $query->latest()->paginate(10);
        $statusLabels = config('orders.order_statuses');

        return view('livewire.admin.order.index', [
            'orders' => $orders,
            'statusLabels' => $statusLabels,
        ])->layout('layouts.admin', ['title' => 'Daftar Pesanan']);
    }
}
