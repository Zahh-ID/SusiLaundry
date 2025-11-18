@extends('layouts.site', ['title' => 'Pesanan Berhasil'])

@section('content')
    @php
        $turnaround = $order->package->turnaround_hours ?? 48;
        $estimatedFinish = $order->created_at?->copy()->addHours($turnaround);
        $estimatedTotal = $order->package ? $order->estimated_weight * $order->package->price_per_kg : null;
    @endphp
    @php
        $latestPayment = $order->payments->first();
    @endphp
    <div class="mx-auto mt-16 w-full max-w-4xl rounded-3xl border border-slate-100 bg-white p-8 shadow-soft">
        <div class="flex flex-col gap-3 text-center">
            <p class="text-sm font-semibold text-primary">Pesanan Berhasil</p>
            <h1 class="text-3xl font-bold text-slate-900">Terima kasih, {{ $order->customer?->name }}!</h1>
            <p class="text-slate-600">Kami sudah menerima pesanan kamu. Simpan detail berikut untuk tracking.</p>
        </div>
        <div class="mt-8 grid gap-6 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Nomor Order</p>
                <p class="text-3xl font-bold tracking-wide text-slate-900">{{ $order->order_code }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Paket & Layanan</p>
                <p class="text-lg font-semibold text-slate-900">{{ $order->package?->package_name }} • {{ ucfirst($order->service_type) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Estimasi Selesai</p>
                <p class="text-lg font-semibold text-slate-900">
                    {{ $estimatedFinish ? $estimatedFinish->translatedFormat('d M Y H:i') : 'Menunggu konfirmasi' }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Estimasi Total</p>
                <p class="text-lg font-semibold text-slate-900">
                    {{ $estimatedTotal ? 'Rp '.number_format($estimatedTotal, 0, ',', '.') : 'Menunggu timbang' }}
                </p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Metode Pembayaran</p>
                <p class="text-lg font-semibold text-slate-900">{{ ucfirst($order->payment_method) }}</p>
                <p class="text-xs text-slate-500">Status: {{ $order->payment_status_label }}</p>
            </div>
            <div class="rounded-2xl border border-slate-100 p-5">
                <p class="text-xs uppercase text-slate-400">Antrian</p>
                <p class="text-lg font-semibold text-slate-900">Nomor {{ $order->queue_position ?? '-' }}</p>
            </div>
        </div>
        @if($order->payment_method === 'qris' && $latestPayment)
            <div class="mt-8 rounded-2xl border border-slate-100 bg-slate-50/70 p-6 text-center">
                <p class="text-sm font-semibold text-slate-700">Scan QRIS untuk melakukan pembayaran</p>
                <img src="{{ $latestPayment->qris_image_url }}" alt="QRIS" class="mx-auto mt-4 h-48 w-48 rounded-xl border border-white shadow-inner">
                <p class="mt-2 text-xs text-slate-500">Kedaluwarsa pada {{ optional($latestPayment->expiry_time)->translatedFormat('d M Y H:i') }}</p>
                <a href="{{ $latestPayment->qris_url }}" target="_blank" class="mt-4 inline-flex items-center justify-center rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white">
                    Buka QRIS
                </a>
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-slate-100 bg-slate-50/70 p-6 text-sm text-slate-600">
                Pembayaran cash dilakukan saat pickup/delivery oleh kurir kami. Mohon siapkan uang pas sesuai estimasi total.
            </div>
        @endif
        <div class="mt-8 flex flex-col gap-3 md:flex-row">
            <a href="{{ route('tracking') }}" class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-center font-semibold text-white hover:bg-indigo-600">
                Cek Status Laundry
            </a>
            <button onclick="window.print()" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 font-semibold text-slate-700 hover:border-primary hover:text-primary">
                Simpan / Cetak Struk
            </button>
            <a href="{{ route('landing') }}" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-center font-semibold text-slate-700 hover:border-primary hover:text-primary">
                Kembali ke Home
            </a>
        </div>
    </div>
@endsection
