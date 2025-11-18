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
        $baseQuery = Order::with(['customer', 'package'])
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status));

        $orders = (clone $baseQuery)->latest()->take(50)->get();

        $summary = [
            'total_orders' => (clone $baseQuery)->count(),
            'total_weight' => (clone $baseQuery)->sum('estimated_weight'),
            'revenue' => (clone $baseQuery)->sum('total_price'),
        ];

        $statusCounts = (clone $baseQuery)
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
