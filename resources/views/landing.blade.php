@extends('layouts.site')

@section('content')
    @php
        $serviceHighlights = [
            [
                'title' => 'Cuci Setrika Express',
                'description' => 'Layanan kilat 24 jam untuk pelanggan super sibuk dengan kualitas premium.',
                'icon' => '⚡️',
            ],
            [
                'title' => 'Antar Jemput Gratis',
                'description' => 'Area Omah Susi di-cover gratis untuk minimal 3kg cucian.',
                'icon' => '🚚',
            ],
            [
                'title' => 'Quality Control 2x',
                'description' => 'Pakaian dicek dua kali sehingga selalu wangi dan rapi.',
                'icon' => '✨',
            ],
        ];

        $steps = [
            'Isi form pemesanan dan pilih paket layanan favorit Anda.',
            'Tim kami menjemput cucian sesuai jadwal yang dipilih.',
            'Laundry dikerjakan profesional dengan standar kebersihan tinggi.',
            'Pesanan dikirim kembali + update status real-time.',
        ];
        $promos = [
            [
                'title' => 'Diskon 15% Pelanggan Baru',
                'description' => 'Masukkan kode NEWFRESH saat checkout pesanan pertama minimal 3kg.',
                'badge' => 'NEWFRESH',
            ],
            [
                'title' => 'Express Kilat 6 Jam',
                'description' => 'Potongan 10% untuk layanan kilat sebelum jam 12:00.',
                'badge' => 'KILAT10',
            ],
        ];
        $aboutPoints = [
            'Operasional sejak 2015 dengan standar kebersihan hotel.',
            'Kurir internal dengan tracking antar-jemput real-time.',
            'Deterjen ramah kulit bayi dan pewangi premium.',
        ];
    @endphp

    <section class="relative overflow-hidden bg-white">
        <div class="mx-auto grid w-full max-w-6xl gap-12 px-6 py-24 lg:grid-cols-2 lg:items-center">
            <div class="space-y-6">
                <p class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-1 text-sm font-semibold text-slate-700 shadow-soft">
                    Laundry bebas drama untuk keluarga dan bisnis Anda
                </p>
                <h1 class="text-4xl font-bold text-slate-900 md:text-5xl">
                    Fresh, wangi, dan rapi <span class="text-primary">tanpa ribet</span>
                </h1>
                <p class="text-lg text-slate-600">
                    Omah Susi Laundry melayani cuci setrika, express, dan antar-jemput hanya dari smartphone Anda. Tracking pesanan real-time tanpa perlu login.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('order.create') }}" class="rounded-2xl border border-primary bg-primary px-6 py-3 font-semibold text-white shadow-lg shadow-primary/30 hover:bg-indigo-600">
                        Pesan Sekarang
                    </a>
                    <a href="{{ route('tracking') }}" class="rounded-2xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 hover:border-primary">
                        Cek Status Pesanan
                    </a>
                </div>
                <dl class="grid grid-cols-2 gap-6 text-sm text-slate-600 md:grid-cols-3">
                    <div>
                        <dt class="text-3xl font-bold text-slate-900">3K+</dt>
                        <dd>Pakaian diselesaikan per bulan</dd>
                    </div>
                    <div>
                        <dt class="text-3xl font-bold text-slate-900">24 Jam</dt>
                        <dd>Layanan express tersedia</dd>
                    </div>
                    <div>
                        <dt class="text-3xl font-bold text-slate-900">4.9/5</dt>
                        <dd>Rating pelanggan</dd>
                    </div>
                </dl>
            </div>
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="space-y-5">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs uppercase text-slate-500">Preview Tracking</p>
                        <p class="text-3xl font-bold tracking-widest text-slate-900">OSL-9082</p>
                        <div class="mt-6 space-y-2 text-sm text-slate-600">
                            <p>Status: <span class="font-semibold text-emerald-600">Sedang Proses</span></p>
                            <p>Paket: Express Kilat</p>
                            <p>Antar: 19.00 - 20.00</p>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-primary bg-primary/90 p-5 text-white">
                        <p class="text-sm font-semibold">Promo Minggu Ini</p>
                        <p class="text-3xl font-bold">Diskon 15%</p>
                        <p class="text-sm text-white/80">Untuk pesanan di atas 5kg setiap Senin-Kamis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="mx-auto w-full max-w-6xl px-6 py-20">
        <div class="mb-10 text-center">
            <p class="text-sm font-semibold text-primary">Kenapa pilih kami?</p>
            <h2 class="text-3xl font-bold text-slate-900">Layanan laundry lengkap & transparan</h2>
            <p class="text-slate-600">Semua kebutuhan laundry keluarga, kos, hingga bisnis bisa ter-cover dengan standar kualitas sama.</p>
        </div>
        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($serviceHighlights as $highlight)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-4xl">{{ $highlight['icon'] }}</p>
                    <h3 class="mt-4 text-xl font-semibold text-slate-900">{{ $highlight['title'] }}</h3>
                    <p class="text-sm text-slate-600">{{ $highlight['description'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="paket" class="bg-white py-20">
        <div class="mx-auto w-full max-w-6xl px-6">
            <div class="mb-10 text-center">
                <p class="text-sm font-semibold text-primary">Daftar Paket</p>
                <h2 class="text-3xl font-bold text-slate-900">Harga jujur, bisa disesuaikan</h2>
                <p class="text-slate-600">Semua paket sudah termasuk deterjen premium, pewangi, dan setrika rapi.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-3">
                @forelse ($packages as $package)
                    <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-sm font-semibold text-primary">{{ $package->package_name }}</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">
                            Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}
                            <span class="text-base font-medium text-slate-500">
                                @if($package->billing_type === 'per_item')
                                    /item
                                @elseif($package->billing_type === 'paket')
                                    /paket
                                @else
                                    /kg
                                @endif
                            </span>
                        </p>
                        <p class="mt-4 flex-1 text-sm text-slate-600">{{ $package->description }}</p>
                        <a href="{{ route('order.create') }}" class="mt-6 rounded-2xl border border-primary bg-primary px-4 py-2 text-center text-white">
                            Pilih Paket Ini
                        </a>
                    </div>
                @empty
                    <p class="text-center text-sm text-slate-500">Belum ada paket terdaftar.</p>
                @endforelse
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-indigo-400">
                    Lihat semua paket →
                </a>
            </div>
        </div>
    </section>

    <section class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-20 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-100 bg-gradient-to-br from-white to-blue-50 p-8 shadow-sm">
            <p class="text-sm font-semibold text-primary">Cara kerja</p>
            <h3 class="text-2xl font-bold text-slate-900">Proses order sederhana</h3>
            <ol class="mt-6 space-y-4 text-slate-600">
                @foreach ($steps as $index => $step)
                    <li class="flex gap-4">
                        <span class="mt-1 h-8 w-8 rounded-full bg-primary/10 text-center text-base font-semibold text-primary">
                            {{ $index + 1 }}
                        </span>
                        <p class="flex-1 pt-1 text-sm">{{ $step }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
        <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold text-primary">Butuh bantuan?</p>
            <h3 class="text-2xl font-bold text-slate-900">Hubungi tim CS kami</h3>
            <p class="mt-2 text-sm text-slate-600">Tanyakan promo, jadwal pick-up, atau request khusus via WhatsApp maupun telepon.</p>
            <div class="mt-8 space-y-3 text-sm font-semibold">
                <a href="https://wa.me/6285645520620" target="_blank" rel="noopener" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 hover:border-primary">
                    <span>WhatsApp Admin</span>
                    <span>+62 856-455-206-20</span>
                </a>
                <a href="tel:+62211234567" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-slate-700 hover:border-primary">
                    <span>Telepon Kantor</span>
                    <span>(021) 123-4567</span>
                </a>
            </div>
            <div class="mt-8 rounded-2xl border border-dashed border-primary/40 bg-primary/5 p-6 text-sm text-slate-600">
                <p class="font-semibold text-slate-900">Tracking real-time</p>
                <p>Masukkan kode tracking Anda kapan saja untuk memantau status pesanan.</p>
                <a href="{{ route('tracking') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-primary">
                    Pergi ke halaman tracking →
                </a>
            </div>
        </div>
    </section>

    <section id="promo" class="bg-white py-20">
        <div class="mx-auto w-full max-w-6xl px-6">
            <div class="mb-10 text-center">
                <p class="text-sm font-semibold text-primary">Promo & Diskon</p>
                <h2 class="text-3xl font-bold text-slate-900">Penawaran spesial untukmu</h2>
                <p class="text-slate-600">Manfaatkan kode promo berikut saat membuat pesanan.</p>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($promos as $promo)
                    <div class="flex flex-col justify-between rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-primary">Promo Laundry</p>
                            <h3 class="mt-2 text-2xl font-bold text-slate-900">{{ $promo['title'] }}</h3>
                            <p class="mt-3 text-sm text-slate-600">{{ $promo['description'] }}</p>
                        </div>
                        <div class="mt-6 flex items-center gap-3">
                            @if ($promo['badge'])
                                <span class="rounded-full border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-900">Kode: {{ $promo['badge'] }}</span>
                            @endif
                            <a href="{{ route('promo') }}" class="text-sm font-semibold text-primary hover:text-indigo-400">Detail promo →</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center text-sm text-slate-400">
                Berlaku untuk guest tanpa login. S&K berlaku.
            </div>
        </div>
    </section>

    <section id="tentang" class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-20 lg:grid-cols-2">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold text-primary">Tentang Kami</p>
            <h2 class="text-3xl font-bold text-slate-900">Kami laundry rumahan yang bersertifikasi</h2>
            <p class="mt-4 text-sm text-slate-600">
                Omah Susi Laundry sudah melayani lebih dari 25.000 pesanan. Semua proses diawasi langsung oleh tim internal untuk memastikan pakaian rapi, higienis, dan tepat waktu.
            </p>
            <ul class="mt-6 space-y-3 text-sm text-slate-600">
                @foreach($aboutPoints as $point)
                    <li>✓ {{ $point }}</li>
                @endforeach
            </ul>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('about') }}" class="rounded-2xl border border-primary bg-primary px-5 py-3 text-center text-sm font-semibold text-white">Baca selengkapnya</a>
                <a href="{{ route('contact') }}" class="rounded-2xl border border-slate-300 bg-white px-5 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">Hubungi Admin</a>
            </div>
        </div>
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold text-primary">Kontak & Lokasi</p>
            <h3 class="text-2xl font-bold text-slate-900">Siap melayani 7 hari seminggu</h3>
            <div class="mt-6 space-y-4 text-sm text-slate-600">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase text-slate-500">Chat WhatsApp</p>
                    <p class="text-lg font-semibold text-slate-900">+62 856-455-206-20</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase text-slate-500">Telepon</p>
                    <p class="text-lg font-semibold text-slate-900">(021) 123-4567</p>
                </div>
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-xs uppercase text-slate-500">Alamat</p>
                    <p class="text-sm text-slate-600">Jl. Melati No. 18, Sleman, Yogyakarta</p>
                </div>
            </div>
            <a href="{{ route('contact') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-indigo-400">
                Lihat peta & kontak lengkap →
            </a>
        </div>
    </section>
@endsection
