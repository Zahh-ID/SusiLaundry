<div class="space-y-8">
    <div>
        <p class="text-sm font-semibold text-primary">Ringkasan Operasional</p>
        <h1 class="text-3xl font-bold text-slate-900">Dashboard Admin</h1>
        <p class="text-sm text-slate-500">Pantau status laundry, pendapatan, dan performa paket terbaru.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Pesanan</p>
            <p class="text-3xl font-bold text-slate-900">{{ $metrics['totalOrders'] }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Hari Ini</p>
            <p class="text-3xl font-bold text-primary">{{ $metrics['todayOrders'] }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Minggu Ini</p>
            <p class="text-3xl font-bold text-amber-500">{{ $metrics['weekOrders'] }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Bulan Ini</p>
            <p class="text-3xl font-bold text-emerald-500">{{ $metrics['monthOrders'] }}</p>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Pendapatan Bulanan</p>
                    <p class="text-4xl font-bold text-slate-900">Rp {{ number_format($metrics['monthlyRevenue'], 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/20">
                    Lihat laporan
                </a>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs uppercase text-slate-400">Order Express</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $metrics['express'] }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-400">Rata-rata berat</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $metrics['averageWeight'] }} kg</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Quick Actions</p>
            <div class="mt-4 grid gap-3 md:grid-cols-2">
                <a href="{{ route('admin.orders.create') }}" class="rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Tambah Pesanan Manual</a>
                <a href="{{ route('admin.orders.index') }}" class="rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Kelola Pesanan</a>
                <a href="{{ route('admin.packages.index') }}" class="rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Kelola Harga & Layanan</a>
                <a href="{{ route('admin.reports.index') }}" class="rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Laporan Transaksi</a>
                <a href="{{ route('admin.customers.index') }}" class="rounded-2xl border border-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Database Pelanggan</a>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Pendapatan tercatat</p>
                    <p class="text-4xl font-bold text-slate-900">Rp {{ number_format($metrics['revenue'], 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/20">
                    Lihat Transaksi
                </a>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-slate-100 p-4">
                    <p class="text-xs uppercase text-slate-400">Order Express</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $metrics['express'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 p-4">
                    <p class="text-xs uppercase text-slate-400">Rata-rata berat</p>
                    <p class="text-2xl font-bold text-slate-900">{{ number_format($metrics['averageWeight'], 1) }} kg</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-slate-500">Paket Teratas</p>
            <p class="text-3xl font-bold text-slate-900">{{ $packages->count() }}</p>
            <ul class="mt-4 space-y-3 text-sm text-slate-600">
                @foreach ($packages->take(3) as $package)
                    <li class="flex items-center justify-between rounded-xl border border-slate-100 px-3 py-2">
                        <span>{{ $package->package_name }}</span>
                        <span class="font-semibold">Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('admin.packages.index') }}" class="mt-5 inline-block text-sm font-semibold text-primary hover:text-indigo-600">
                Kelola paket →
            </a>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pesanan Terbaru</h2>
                <p class="text-sm text-slate-500">5 transaksi terakhir beserta statusnya.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-primary hover:text-indigo-600">
                Lihat semua
            </a>
        </div>
        <div class="mt-6 divide-y divide-slate-100 text-sm">
            @forelse ($recentOrders as $order)
                <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $order->customer?->name }}</p>
                        <p class="text-xs text-slate-500">{{ $order->package?->package_name }} • {{ $order->order_code }}</p>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $order->status_label }}</span>
                        <span class="font-semibold">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <p class="py-6 text-center text-sm text-slate-500">Belum ada transaksi.</p>
            @endforelse
        </div>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="text-xl font-bold text-slate-900">Status Pesanan</h2>
        <div class="mt-4 grid gap-3 md:grid-cols-4">
            @foreach($statuses as $key => $label)
                <div class="rounded-2xl border border-slate-100 p-3 text-sm">
                    <p class="text-slate-500">{{ $label }}</p>
                    <p class="text-2xl font-bold text-slate-900">{{ $metrics['statusCounts'][$key] ?? 0 }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
