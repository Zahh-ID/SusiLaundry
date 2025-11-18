<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-primary">Kelola Transaksi</p>
            <h1 class="text-3xl font-bold text-slate-900">Transaksi Masuk</h1>
            <p class="text-sm text-slate-500">Kelola status pesanan, berat aktual, dan biaya akhir.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.orders.create') }}" class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                Tambah Pesanan Manual
            </a>
            <a href="{{ route('admin.reports.orders.export', ['status' => $statusFilter !== 'all' ? $statusFilter : null, 'service_type' => $serviceTypeFilter !== 'all' ? $serviceTypeFilter : null, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}"
               class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-primary hover:text-primary">
                Export CSV
            </a>
        </div>
    </div>

    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="grid gap-4 lg:grid-cols-5">
            <div>
                <label class="text-sm font-semibold text-slate-600" for="search">Cari pesanan</label>
                <input id="search" type="text" placeholder="Nama, paket, atau kode" wire:model.debounce.500ms="search"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600" for="status">Status</label>
                <select id="status" wire:model.live="statusFilter"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="all">Semua</option>
                    @foreach($availableStatuses as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600" for="service-type">Layanan</label>
                <select id="service-type" wire:model.live="serviceTypeFilter"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="all">Semua</option>
                    <option value="regular">Regular</option>
                    <option value="express">Express</option>
                    <option value="kilat">Kilat</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600" for="date-from">Dari</label>
                <input id="date-from" type="date" wire:model.live="dateFrom"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600" for="date-to">Sampai</label>
                <input id="date-to" type="date" wire:model.live="dateTo"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2">Pelanggan</th>
                        <th class="px-4 py-2">Paket</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Pembayaran</th>
                        <th class="px-4 py-2">Estimasi / Aktual</th>
                        <th class="px-4 py-2">Antrian</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-slate-900">{{ $order->customer?->name }}</p>
                                <p class="text-xs text-slate-500">{{ $order->order_code }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $order->package?->package_name }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $order->status_label }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <p>{{ ucfirst($order->payment_method) }}</p>
                                <p class="text-xs text-slate-400">{{ $order->payment_status_label }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $order->estimated_weight }}kg
                                @if($order->actual_weight)
                                    <span class="text-xs text-slate-400">→ {{ $order->actual_weight }}kg</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                {{ $order->queue_position ?? '-' }}
                            </td>
                            <td class="px-4 py-3 font-semibold">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 space-x-3 text-sm font-semibold">
                                <a href="{{ route('admin.orders.edit', $order) }}" class="text-primary hover:text-indigo-600">
                                    Edit
                                </a>
                                <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="text-slate-500 hover:text-slate-700">
                                    Print
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">
                                Tidak ada data pesanan untuk filter saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
