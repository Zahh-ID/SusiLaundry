<div>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-primary">Kelola Transaksi</p>
                <h1 class="text-3xl font-bold text-slate-900">Transaksi Masuk</h1>
                <p class="text-sm text-slate-500">Kelola status pesanan, berat aktual, dan biaya akhir.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" wire:click="openCreateModal"
                    class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                    Tambah Pesanan Manual
                </button>
                <button type="button" wire:click="openExportModal"
                    class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-primary hover:text-primary">
                    Export CSV
                </button>
            </div>
        </div>

        <div class="flex gap-3 overflow-x-auto pb-3">
            @foreach($statusLabels as $status => $label)
                <button type="button" wire:key="status-nav-{{ $status }}" wire:click="$set('statusFilter', '{{ $status }}')"
                    class="min-w-[150px] rounded-2xl border px-4 py-2 text-left transition-all flex-grow" @class([
                        'border-primary bg-primary text-white shadow-lg' => $statusFilter === $status,
                        'border-slate-200 bg-slate-50 text-slate-600' => $statusFilter !== $status,
                    ])>
                    <p class="text-sm font-semibold">{{ $label }}</p>
                </button>
            @endforeach
        </div>

        @if (session()->has('message'))
            <div
                class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-sm sm:p-6">
            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label class="text-sm font-semibold text-slate-600" for="search">Cari pesanan</label>
                    <input id="search" type="text" placeholder="Nama, paket, atau kode"
                        wire:model.debounce.500ms="search"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
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

            <div class="mt-6 space-y-4">
                @forelse($orders as $order)
                    <div class="rounded-2xl border border-slate-100 bg-white/80 p-4 shadow-sm"
                        wire:key="order-card-{{ $order->id }}">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $order->customer?->name ?? 'Tanpa Nama' }}</p>
                                <p class="text-xs text-slate-500">{{ $order->order_code }}</p>
                            </div>
                            <div class="text-right text-sm text-slate-600">
                                <p>{{ $order->package?->package_name ?? 'Tanpa Paket' }}</p>
                                <p class="text-xs text-slate-400">{{ ucfirst($order->service_type ?? '-') }} •
                                    {{ $order->status_label }}</p>
                            </div>
                        </div>
                        <div class="mt-4 grid gap-4 text-sm sm:grid-cols-4">
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-500">Pembayaran</p>
                                <p class="text-sm text-slate-900">{{ ucfirst($order->payment_method ?? '-') }}</p>
                                <p class="text-xs text-slate-400">{{ $order->payment_status_label }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-500">Berat</p>
                                <p class="text-sm text-slate-900">{{ $order->estimated_weight }}kg
                                    @if($order->actual_weight)
                                        <span class="text-xs text-slate-400">→ {{ $order->actual_weight }}kg</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-500">Antrian</p>
                                <p class="text-sm text-slate-900">{{ $order->queue_position ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-slate-500">Total</p>
                                <p class="text-lg font-bold text-slate-900">Rp
                                    {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <button type="button"
                                class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600"
                                wire:click="openAdvanceModal({{ $order->id }})">
                                Proses Pesanan
                            </button>
                            <button type="button"
                                class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-rose-500 hover:text-rose-500"
                                wire:click="openCancelModal({{ $order->id }})">
                                Batalkan Pesanan
                            </button>
                        </div>
                    </div>
                @empty
                    <div
                        class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        Belum ada pesanan yang cocok dengan filter Anda.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modals --}}
    <livewire:admin.order.modals.create-order />
    <livewire:admin.order.modals.advance-order />
    <livewire:admin.order.modals.cancel-order />
    <livewire:admin.order.modals.export-orders />
</div>
```