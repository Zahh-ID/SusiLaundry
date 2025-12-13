<div class="space-y-8">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
            <p class="text-sm text-slate-500">Selamat datang kembali, {{ auth()->user()->name }}!</p>
        </div>
        <button wire:click="openCreateOrderModal"
            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path
                    d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
            </svg>
            Buat Pesanan Baru
        </button>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Orders --}}
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
            <dt class="truncate text-sm font-medium text-slate-500">Total Pesanan</dt>
            <dd class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-slate-900">{{ $metrics['totalOrders'] }}</span>
                <span class="text-xs text-slate-500">transaksi</span>
            </dd>
        </div>

        {{-- Today's Orders --}}
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
            <dt class="truncate text-sm font-medium text-slate-500">Pesanan Hari Ini</dt>
            <dd class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-indigo-600">{{ $metrics['todayOrders'] }}</span>
                @if($metrics['todayOrders'] > 0)
                    <span
                        class="inline-flex items-baseline rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700 md:mt-2 lg:mt-0">
                        <svg class="-ml-1 mr-0.5 h-4 w-4 flex-shrink-0 self-center text-green-500" fill="currentColor"
                            viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z"
                                clip-rule="evenodd" />
                        </svg>
                        Active
                    </span>
                @endif
            </dd>
        </div>

        {{-- Revenue --}}
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
            <dt class="truncate text-sm font-medium text-slate-500">Pendapatan Bulan Ini</dt>
            <dd class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-semibold text-slate-900">Rp
                    {{ number_format($metrics['monthlyRevenue'] / 1000, 0, ',', '.') }}k</span>
            </dd>
        </div>

        {{-- Active Orders (Processing) --}}
        <div class="overflow-hidden rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-900/5">
            <dt class="truncate text-sm font-medium text-slate-500">Sedang Proses</dt>
            <dd class="mt-2 flex items-baseline gap-2">
                <span
                    class="text-3xl font-semibold text-amber-500">{{ $metrics['statusCounts']['processing'] ?? 0 }}</span>
                <span class="text-xs text-slate-500">cucian</span>
            </dd>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Recent Orders Table (Span 2) --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold leading-6 text-slate-900">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Lihat semua <span
                        aria-hidden="true">&rarr;</span></a>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Order ID</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Pelanggan</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @forelse ($recentOrders as $order)
                                                        <tr>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-indigo-600">
                                                                <a href="{{ route('admin.orders.index') }}"
                                                                    class="hover:underline">{{ $order->order_code }}</a>
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-900">
                                                                {{ $order->customer?->name ?? 'Guest' }}
                                                                <div class="text-xs text-slate-500">{{ $order->package?->package_name }}</div>
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                                                                                                                                                        {{ match ($order->status) {
                                    'completed' => 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20',
                                    'processing', 'washing' => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10',
                                    'pending' => 'bg-yellow-50 text-yellow-800 ring-1 ring-inset ring-yellow-600/20',
                                    'cancelled' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10',
                                    default => 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-500/10',
                                } }}">
                                                                    {{ ucfirst($order->status) }}
                                                                </span>
                                                            </td>
                                                            <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-slate-500">
                                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">
                                        Belum ada pesanan terbaru.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Side Widgets --}}
        <div class="space-y-8">
            {{-- Package Performance --}}
            <div>
                <h3 class="text-base font-semibold leading-6 text-slate-900 mb-4">Paket Terlaris</h3>
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <ul role="list" class="divide-y divide-slate-100">
                        @foreach ($packages->take(5) as $package)
                            <li class="flex items-center justify-between gap-x-6 px-6 py-4 hover:bg-slate-50">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold leading-6 text-slate-900">{{ $package->package_name }}
                                    </p>
                                    <p class="mt-1 truncate text-xs leading-5 text-slate-500">Rp
                                        {{ number_format($package->price_per_kg, 0, ',', '.') }} /
                                        {{ $package->billing_type == 'per_kg' ? 'kg' : 'item' }}
                                    </p>
                                </div>
                                <div class="flex flex-none items-center gap-x-4">
                                    <a href="{{ route('admin.packages.index') }}"
                                        class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:block">Kelola</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Order Modal --}}
    @if($showCreateOrderModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-3 py-5">
            <div
                class="relative w-full max-w-4xl h-[88vh] max-h-[90vh] overflow-hidden rounded-3xl bg-white shadow-2xl animate-fade-in-up">
                <button type="button"
                    class="absolute right-6 top-6 z-10 inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow hover:border-primary hover:text-primary"
                    wire:click="closeCreateOrderModal">
                    ✕ <span>Tutup</span>
                </button>
                <div class="h-full overflow-y-auto p-6">
                    <livewire:admin.order.create :embedded="true" wire:key="dashboard-create-order" />
                </div>
            </div>
            <style>
                body {
                    overflow: hidden;
                }
            </style>
        </div>
    @endif
</div>