@extends('layouts.site', ['title' => 'Promo Laundry'])

@section('content')
    @php
        $promos = [
            [
                'title' => 'Diskon 15% Pelanggan Baru',
                'description' => 'Gunakan kode NEWFRESH saat pemesanan pertama minimal 3kg.',
                'periode' => 'Berlaku s.d 31 Desember 2025',
                'code' => 'NEWFRESH',
            ],
            [
                'title' => 'Gratis Antar Jemput di Dalam Kota',
                'description' => 'Untuk pesanan di atas 5kg, kapan pun dan tanpa batas.',
                'periode' => 'Berlaku setiap hari',
                'code' => null,
            ],
            [
                'title' => 'Express Kilat 6 Jam - Hemat 10%',
                'description' => 'Pesanan kilat sebelum jam 12:00 dapat potongan 10%.',
                'periode' => 'Senin - Kamis',
                'code' => 'KILAT10',
            ],
        ];
    @endphp
    <section class="mx-auto mt-16 w-full max-w-5xl px-6">
        <div class="text-center">
            <p class="text-sm font-semibold text-primary">Promo & Diskon</p>
            <h1 class="text-4xl font-bold text-slate-900">Belanja hemat, hasil tetap maksimal</h1>
            <p class="mt-3 text-slate-600">Manfaatkan promo berikut untuk memesan layanan laundry favoritmu.</p>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-2">
            @foreach($promos as $promo)
                <div class="flex flex-col justify-between rounded-3xl border border-slate-100 bg-white p-6 shadow-soft">
                    <div>
                        <p class="text-sm font-semibold text-primary">Promo Laundry</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900">{{ $promo['title'] }}</h2>
                        <p class="mt-3 text-sm text-slate-600">{{ $promo['description'] }}</p>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $promo['periode'] }}</p>
                    </div>
                    <div class="mt-6 flex items-center gap-3">
                        @if($promo['code'])
                            <span class="rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold uppercase tracking-wider text-white">
                                Kode: {{ $promo['code'] }}
                            </span>
                        @endif
                        <a href="{{ route('order.create') }}" class="rounded-full border border-primary px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/10">
                            Gunakan Promo
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-10 text-center text-sm text-slate-500">
            Syarat & ketentuan berlaku. Promo hanya berlaku untuk Guest dan tidak dapat digabung dengan promo lain.
        </div>
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('order.create') }}" class="rounded-2xl bg-primary px-5 py-3 text-center text-sm font-semibold text-white">Buat Pesanan</a>
            <a href="{{ route('landing') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Kembali</a>
        </div>
    </section>
@endsection
