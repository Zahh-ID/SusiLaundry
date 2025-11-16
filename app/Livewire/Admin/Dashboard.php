<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Package;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();
        $monthStart = now()->startOfMonth();

        $orders = Order::with(['customer', 'package'])->latest()->take(5)->get();

        $metrics = [
            'totalOrders' => Order::count(),
            'todayOrders' => Order::whereDate('created_at', $today)->count(),
            'weekOrders' => Order::whereBetween('created_at', [$weekStart, now()])->count(),
            'monthOrders' => Order::whereBetween('created_at', [$monthStart, now()])->count(),
            'statusCounts' => Order::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status')->all(),
            'revenue' => Order::sum('total_price') ?? 0,
            'monthlyRevenue' => Order::whereBetween('created_at', [$monthStart, now()])->sum('total_price') ?? 0,
            'express' => Order::where('service_type', '!=', 'regular')->count(),
            'averageWeight' => round(Order::avg('estimated_weight') ?? 0, 1),
        ];

        $packages = Package::orderBy('price_per_kg')->get();

        return view('livewire.admin.dashboard', [
            'metrics' => $metrics,
            'recentOrders' => $orders,
            'packages' => $packages,
            'statuses' => config('orders.order_statuses'),
        ])->layout('layouts.admin', ['title' => 'Dashboard Admin']);
    }
}
