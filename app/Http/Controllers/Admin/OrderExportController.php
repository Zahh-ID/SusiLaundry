<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $fileName = 'orders-export-'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($request) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Kode', 'Nama Pelanggan', 'Status', 'Layanan', 'Estimasi', 'Berat Aktual', 'Total', 'Tanggal']);

            $query = Order::with(['customer', 'package'])
                ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
                ->when($request->filled('service_type'), fn ($q) => $q->where('service_type', $request->service_type))
                ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
                ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
                ->latest()
                ->get();

            foreach ($query as $order) {
                fputcsv($handle, [
                    $order->order_code,
                    $order->customer?->name,
                    $order->status,
                    $order->service_type,
                    $order->estimated_weight,
                    $order->actual_weight,
                    $order->total_price,
                    $order->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
