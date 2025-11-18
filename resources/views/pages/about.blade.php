@extends('layouts.site', ['title' => 'Tentang Omah Susi Laundry'])

@section('content')
    <section class="mx-auto mt-16 w-full max-w-5xl px-6">
        <div class="grid gap-10 lg:grid-cols-2">
            <div>
                <p class="text-sm font-semibold text-primary">Tentang Kami</p>
                <h1 class="text-4xl font-bold text-slate-900">Omah Susi Laundry</h1>
                <p class="mt-4 text-slate-600">
                    Kami adalah tim laundry rumahan profesional yang sudah beroperasi sejak 2015.
                    Visi kami sederhana: membuat urusan laundry keluarga maupun bisnis jadi bebas drama.
                    Semua proses dilakukan dengan peralatan higienis, deterjen ramah kulit, dan quality control dua kali.
                </p>
                <ul class="mt-6 space-y-3 text-sm text-slate-600">
                    <li>✓ Layanan cuci setrika reguler, express, hingga kilat.</li>
                    <li>✓ Antar jemput gratis untuk area dalam kota.</li>
                    <li>✓ Tracking status real-time tanpa perlu login.</li>
                    <li>✓ Tim kurir dan operator yang responsif.</li>
                </ul>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('order.create') }}" class="rounded-2xl border border-primary bg-primary px-5 py-3 text-center text-sm font-semibold text-white">Buat Pesanan</a>
                    <a href="{{ route('contact') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Hubungi Admin</a>
                </div>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-soft">
                <h2 class="text-xl font-semibold text-slate-900">Kenapa memilih kami?</h2>
                <div class="mt-5 space-y-4 text-sm text-slate-600">
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">Operasional Transparan</p>
                        <p>Harga jelas, status pesanan selalu bisa dicek tanpa login.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">Tim Profesional</p>
                        <p>Kurir dan operator terlatih untuk memastikan pakaian rapi dan aman.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 p-4">
                        <p class="font-semibold text-slate-900">Layanan Pelanggan Responsif</p>
                        <p>Hubungi WhatsApp atau telepon kapan saja untuk bantuan.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-12 text-center">
            <a href="{{ route('landing') }}" class="text-sm font-semibold text-slate-500 hover:text-primary">← Kembali ke Home</a>
        </div>
    </section>
@endsection
