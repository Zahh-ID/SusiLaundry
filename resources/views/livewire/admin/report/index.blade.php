<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-primary">Laporan Transaksi</p>
            <h1 class="text-3xl font-bold text-slate-900">Laporan Pendapatan & Pesanan</h1>
            <p class="text-sm text-slate-500">Filter berdasarkan tanggal atau status lalu export ke Excel.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.orders.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'status' => $status !== 'all' ? $status : null]) }}"
               class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-primary hover:text-primary">
                Export CSV
            </a>
        </div>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="grid gap-4 md:grid-cols-4">
            <div>
                <label class="text-sm font-semibold text-slate-600">Dari Tanggal</label>
                <input type="date" wire:model.live="dateFrom" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600">Sampai Tanggal</label>
                <input type="date" wire:model.live="dateTo" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600">Status</label>
                <select wire:model.live="status" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-2 text-sm">
                    <option value="all">Semua</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Pesanan</p>
            <p class="text-3xl font-bold text-slate-900">{{ $summary['total_orders'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Berat (kg)</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($summary['total_weight'] ?? 0, 1) }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pendapatan</p>
            <p class="text-3xl font-bold text-slate-900">Rp {{ number_format($summary['revenue'] ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Status Pesanan</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-4">
            @foreach($statuses as $key => $label)
                <div class="rounded-2xl border border-slate-100 p-3 text-sm">
                    <p class="text-slate-500">{{ $label }}</p>
                    <p class="text-xl font-bold text-slate-900">{{ $statusCounts[$key] ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">50 Pesanan Terbaru</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2">Kode</th>
                        <th class="px-4 py-2">Pelanggan</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold">{{ $order->order_code }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $order->customer?->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $order->status_label }}</td>
                            <td class="px-4 py-3 font-semibold">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada data untuk rentang ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
