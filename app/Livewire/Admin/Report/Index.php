<?php

namespace App\Livewire\Admin\Report;

use App\Models\Order;
use Livewire\Component;

class Index extends Component
{
    public $dateFrom;
    public $dateTo;
    public $status = 'all';

    public function render()
    {
        $query = Order::with(['customer', 'package'])
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status));

        $orders = $query->latest()->take(50)->get();

        $summary = [
            'total_orders' => $query->count(),
            'total_weight' => $query->sum('estimated_weight'),
            'revenue' => $query->sum('total_price'),
        ];

        $statusCounts = (clone $query)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('livewire.admin.report.index', [
            'orders' => $orders,
            'summary' => $summary,
            'statusCounts' => $statusCounts,
            'statuses' => config('orders.order_statuses'),
        ])->layout('layouts.admin', ['title' => 'Laporan Transaksi']);
    }
}
