<?php

namespace App\Livewire\Admin\Order;

use App\Models\Order;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $serviceTypeFilter = 'all';
    public $dateFrom;
    public $dateTo;
    public array $availableStatuses = [];

    public function render()
    {
        $this->availableStatuses = config('orders.order_statuses');

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
        ])->layout('layouts.admin', ['title' => 'Daftar Transaksi']);
    }
}
