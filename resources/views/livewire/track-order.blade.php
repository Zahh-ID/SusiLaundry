@php
    $statusMap = config('orders.order_statuses');
    $statusKeys = array_keys($statusMap);
    $timelineSteps = array_values($statusMap);
    $statusIndex = $order ? array_search($order->status, $statusKeys, true) : -1;
@endphp
<div class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-16 lg:grid-cols-2">
    <div>
        <p class="text-sm font-semibold text-primary">Tracking Pesanan</p>
        <h1 class="mb-3 text-4xl font-bold text-slate-900">Pantau status kapan saja</h1>
        <p class="text-slate-600">Masukkan kode tracking (10 karakter) yang kami kirim lewat email. Status pesanan akan
            tampil otomatis.</p>
        <div class="mt-10 space-y-4 rounded-3xl border border-slate-100 bg-slate-50 p-6">
            <p class="text-sm font-semibold text-slate-700">Status Pesanan</p>
            <ul class="space-y-3 text-sm text-slate-500">
                @foreach($timelineSteps as $index => $step)
                    <li
                        class="{{ $order && $statusIndex !== false && $index <= $statusIndex ? 'font-semibold text-slate-900' : '' }}">
                        {{ $step }}
                        @if ($index === 0)
                            <span class="text-xs text-slate-400">— pesanan diterima dan menunggu pick up.</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
        <a href="{{ route('landing') }}"
            class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-indigo-600">
            ← Kembali ke Home
        </a>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-soft">
        <form wire:submit.prevent="track" class="space-y-4">
            <div>
                <label for="order_code" class="text-sm font-semibold text-slate-600">Kode Tracking</label>
                <input type="text" id="order_code" wire:model.defer="order_code" placeholder="Masukkan kode"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('order_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit"
                class="w-full rounded-2xl border border-primary bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                Cek Status
            </button>
        </form>

        @if ($errorMessage)
            <div class="mt-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
                {{ $errorMessage }}
            </div>
        @endif

        @if ($order)
            @php
                $progressSteps = $timelineSteps;
                $activeIndex = array_search($order->status, $statusKeys, true);
            @endphp
            <div class="mt-6 space-y-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-5 text-sm text-slate-600"
                wire:poll.visible.15s="refreshStatus">
                @if($order->payment_method === 'qris' && $order->payment_status !== 'paid')
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                        Silakan selesaikan pembayaran terlebih dahulu untuk melanjutkan proses pengambilan.
                    </div>
                @endif
                <div>
                    <p class="text-xs uppercase text-slate-400">Kode Pesanan</p>
                    <p class="text-2xl font-bold tracking-wide text-slate-900">{{ $order->order_code }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase text-slate-400">Status</p>
                        <p class="text-lg font-semibold text-primary">{{ $order->status_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-slate-400">Estimasi Berat</p>
                        <p class="text-lg font-semibold">{{ $order->estimated_weight }} kg</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-slate-400">Paket</p>
                        <p class="text-lg font-semibold">{{ $order->package?->package_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-slate-400">Layanan</p>
                        <p class="text-lg font-semibold capitalize">{{ $order->service_type }}</p>
                    </div>
                </div>
                @if($order->total_price)
                    <div>
                        <p class="text-xs uppercase text-slate-400">Total Biaya</p>
                        <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </p>
                    </div>
                @endif
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase text-slate-400">Metode Pembayaran</p>
                        <p class="text-lg font-semibold text-slate-900">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-slate-400">Status Pembayaran</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $order->payment_status_label }}</p>
                    </div>
                </div>
                <div class="pt-4">
                    <p class="text-xs uppercase text-slate-400">Progress</p>
                    <ul class="mt-3 space-y-2">
                        @foreach($progressSteps as $index => $label)
                            <li
                                class="flex items-center gap-3 {{ $activeIndex !== false && $index <= $activeIndex ? 'text-slate-900 font-semibold' : '' }}">
                                <span
                                    class="flex h-6 w-6 items-center justify-center rounded-full {{ $activeIndex !== false && $index <= $activeIndex ? 'bg-primary text-white' : 'bg-slate-200 text-slate-500' }}">
                                    {{ $index + 1 }}
                                </span>
                                <span>{{ $label }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if($order->payment_method === 'qris')
                    @if($order->status === 'pending_confirmation')
                        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 text-center">
                            <p class="text-sm font-semibold text-blue-800">Menunggu Konfirmasi Laundry</p>
                            <p class="text-xs text-blue-600 mt-1">QRIS akan muncul setelah pesanan diproses oleh admin.</p>
                        </div>
                    @elseif($order->payment_status === 'paid')
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-2">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-emerald-800">Pembayaran Berhasil</p>
                            <p class="text-xs text-emerald-600">Terima kasih, pembayaran Anda telah terkonfirmasi.</p>
                        </div>
                    @else
                        @php
                            $pendingPayment = $order->payments->firstWhere('status', 'pending') ?? $order->payments->first();
                        @endphp
                        @if($pendingPayment && $pendingPayment->qris_image_url)
                            <div class="rounded-2xl border border-slate-100 bg-white p-4 text-center">
                                <p class="text-sm font-semibold text-slate-700">Scan QRIS untuk melakukan pembayaran</p>
                                <img src="{{ $pendingPayment->qris_image_url }}" alt="QRIS"
                                    class="mx-auto mt-4 h-40 w-40 rounded-xl border border-slate-200">
                                <p class="mt-2 text-xs text-slate-500">Kedaluwarsa pada
                                    {{ optional($pendingPayment->expiry_time)->translatedFormat('d M Y H:i') }}
                                </p>
                                <p class="mt-2 text-xs text-slate-500">Status akan diperbarui otomatis setelah pembayaran terkonfirmasi.
                                </p>
                            </div>
                        @else
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-center">
                                <p class="text-sm font-semibold text-amber-800">Menunggu Data Pembayaran</p>
                                <p class="text-xs text-amber-700 mt-1">Silakan hubungi admin jika QRIS tidak muncul.</p>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 text-sm text-slate-600">
                        Pembayaran cash akan diproses oleh kurir kami saat pickup/delivery.
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>