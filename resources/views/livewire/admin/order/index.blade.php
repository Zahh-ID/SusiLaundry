<div>
<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-primary">Kelola Transaksi</p>
            <h1 class="text-3xl font-bold text-slate-900">Transaksi Masuk</h1>
            <p class="text-sm text-slate-500">Kelola status pesanan, berat aktual, dan biaya akhir.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <button type="button" wire:click="openCreateModal" class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                Tambah Pesanan Manual
            </button>
            <button type="button" wire:click="openExportModal" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-primary hover:text-primary">
                Export CSV
            </button>
        </div>
    </div>

    <div class="flex gap-3 overflow-x-auto pb-3">
        @foreach($statusLabels as $status => $label)
            <button type="button"
                    wire:key="status-nav-{{ $status }}"
                    wire:click="$set('statusFilter', '{{ $status }}')"
                    class="min-w-[150px] rounded-2xl border px-4 py-2 text-left transition-all flex-grow"
                    @class([
                        'border-primary bg-primary text-white shadow-lg' => $statusFilter === $status,
                        'border-slate-200 bg-slate-50 text-slate-600' => $statusFilter !== $status,
                    ])
            >
                <p class="text-sm font-semibold">{{ $label }}</p>
            </button>
        @endforeach
    </div>

    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
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
                <input id="search" type="text" placeholder="Nama, paket, atau kode" wire:model.debounce.500ms="search"
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
                <div class="rounded-2xl border border-slate-100 bg-white/80 p-4 shadow-sm" wire:key="order-card-{{ $order->id }}">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $order->customer?->name ?? 'Tanpa Nama' }}</p>
                            <p class="text-xs text-slate-500">{{ $order->order_code }}</p>
                        </div>
                        <div class="text-right text-sm text-slate-600">
                            <p>{{ $order->package?->package_name ?? 'Tanpa Paket' }}</p>
                            <p class="text-xs text-slate-400">{{ ucfirst($order->service_type ?? '-') }} • {{ $order->status_label }}</p>
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
                            <p class="text-lg font-bold text-slate-900">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button type="button" class="rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600" wire:click="openAdvanceModal({{ $order->id }})">
                            Proses Pesanan
                        </button>
                        <button type="button"
                                class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:border-rose-500 hover:text-rose-500"
                                wire:click="openCancelModal({{ $order->id }})"
                        >
                            Batalkan Pesanan
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">
                    Belum ada pesanan yang cocok dengan filter Anda.
                </div>
            @endforelse
        </div>
    </div>
</div>

@if($showCreateModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                    <h2 class="text-2xl font-bold text-slate-900">Buka Form Manual?</h2>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" wire:click="closeCreateModal">Tutup</button>
            </div>
            <p class="mt-3 text-sm text-slate-600">Anda akan diarahkan ke halaman input pesanan manual.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button type="button" class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600" wire:click="confirmCreate">
                    Ya, buka form
                </button>
                <button type="button" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary" wire:click="closeCreateModal">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endif

@if($showExportModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                    <h2 class="text-2xl font-bold text-slate-900">Export CSV?</h2>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" wire:click="closeExportModal">Tutup</button>
            </div>
            <p class="mt-3 text-sm text-slate-600">File akan diunduh berdasarkan filter saat ini.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button type="button" class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600" wire:click="confirmExport">
                    Export Sekarang
                </button>
                <button type="button" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary" wire:click="closeExportModal">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endif

@if($showAdvanceModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                    <h2 class="text-2xl font-bold text-slate-900">Proses Pesanan?</h2>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" wire:click="closeAdvanceModal">Tutup</button>
            </div>
            @if(!empty($advanceSummary))
                <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600 space-y-2">
                    @foreach($advanceSummary as $label => $value)
                        <div>
                            <p class="text-xs uppercase text-slate-400">{{ $label }}</p>
                            <p class="font-semibold text-slate-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            @if($advanceCurrentStatus === 'pending_confirmation')
                <div class="mt-4">
                    <label class="text-sm font-semibold text-slate-600" for="actual_weight">Berat Aktual (kg)</label>
                    <input id="actual_weight" type="number" step="0.1" wire:model.defer="actual_weight"
                           class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('actual_weight')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                @if($advanceCurrentStatus === 'pending_confirmation')
                    <button type="button" class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600" wire:click="confirmAdvanceWithWeight">
                        Simpan Berat & Lanjutkan
                    </button>
                @endif
                <button type="button" class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600" wire:click="confirmAdvance">
                    Lanjutkan
                </button>
                <button type="button" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary" wire:click="closeAdvanceModal">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endif

@if($showCancelModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                    <h2 class="text-2xl font-bold text-slate-900">Batalkan Pesanan?</h2>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" wire:click="closeCancelModal">Tutup</button>
            </div>
            @if(!empty($cancelSummary))
                <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600 space-y-2">
                    @foreach($cancelSummary as $label => $value)
                        <div>
                            <p class="text-xs uppercase text-slate-400">{{ $label }}</p>
                            <p class="font-semibold text-slate-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <button type="button" class="flex-1 rounded-2xl border border-rose-200 bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700" wire:click="confirmCancel">
                    Ya, batalkan
                </button>
                <button type="button" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary" wire:click="closeCancelModal">
                    Batal
                </button>
            </div>
        </div>
    </div>
@endif
</div>
