@extends('layouts.site')

@section('content')
    {{-- Hero Section (Split Layout) --}}
    <section class="relative overflow-hidden bg-white pt-24 pb-16 lg:pt-32 lg:pb-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
                {{-- Text Content --}}
                <div class="max-w-2xl animate-fade-in-up">
                    <div class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-600 mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
                        Laundry Premium #1 di Yogyakarta
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl mb-6 leading-tight">
                        Cuci Bersih, <br>
                        <span class="text-indigo-600">Hidup Lebih Santai.</span>
                    </h1>
                    <p class="text-lg leading-relaxed text-slate-600 mb-8 pr-0 lg:pr-12">
                        Serahkan urusan cucian kotor kepada kami. Layanan antar-jemput gratis, tracking real-time, dan garansi wangi tahan lama.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('order.create') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-8 py-4 text-base font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all hover:bg-indigo-500 hover:scale-[1.02] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Pesan Sekarang
                        </a>
                        <a href="#paket" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-8 py-4 text-base font-semibold text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900">
                            Lihat Harga
                        </a>
                    </div>
                    
                    <div class="mt-10 flex items-center gap-4 text-sm text-slate-500">
                        <div class="flex -space-x-2">
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=64&h=64" alt=""/>
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=64&h=64" alt=""/>
                            <img class="inline-block h-8 w-8 rounded-full ring-2 ring-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=64&h=64" alt=""/>
                        </div>
                        <p>Dipercaya 3.000+ pelanggan</p>
                    </div>
                </div>

                {{-- Hero Image / Dynamic Orders --}}
                <div class="relative lg:h-full animate-fade-in-up animation-delay-200 flex items-center justify-center">
                    <div class="relative w-full max-w-md">
                        {{-- Background Decor --}}
                        <div class="absolute -top-4 -right-4 h-72 w-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                        <div class="absolute -bottom-8 -left-4 h-72 w-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
                        
                        {{-- Orders Box --}}
                        <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 p-6 overflow-hidden">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Pesanan Selesai</h3>
                                    <p class="text-xs text-slate-500">Real-time update</p>
                                </div>
                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 animate-pulse">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-4">
                                @forelse($completedOrders as $order)
                                    <div class="flex items-center gap-4 p-3 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all duration-300">
                                        <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            {{ substr($order->customer->name ?? 'G', 0, 1) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-bold text-slate-900">{{ Str::mask($order->order_code, '*', 4) }}</p>
                                                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Selesai</span>
                                            </div>
                                            <p class="text-xs text-slate-500">{{ $order->package->package_name ?? 'Layanan Laundry' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8 text-slate-500 text-sm">
                                        Belum ada pesanan selesai hari ini.
                                    </div>
                                @endforelse
                            </div>
                            
                            <div class="mt-6 pt-4 border-t border-slate-100 text-center">
                                <p class="text-xs text-slate-500">Bergabung dengan 3.000+ pelanggan puas lainnya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    {{-- Features Section (Clean Cards) --}}
    <section id="layanan" class="py-24 bg-slate-50">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">Kenapa Omah Susi?</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Standar Hotel, Harga Anak Kos</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Express 6 Jam</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Layanan kilat untuk kebutuhan mendesak. Pakaian dijamin kering sempurna, rapi, dan wangi dalam waktu singkat.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Tracking Real-time</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Pantau status cucian Anda kapan saja. Notifikasi WhatsApp otomatis saat kurir menjemput dan cucian selesai.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
                    <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center text-green-600 mb-6">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Gratis Antar Jemput</h3>
                    <p class="text-slate-600 leading-relaxed">
                        Hemat waktu dan tenaga. Kurir kami siap menjemput cucian kotor Anda. Gratis ongkir untuk area radius 3km.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Pricing Section --}}
    <section id="paket" class="py-24 bg-white relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 lg:px-8 relative">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">Pricing</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Pilih Paket Hemat Anda</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto items-start">
                @php
                    $packages = \App\Models\Package::take(3)->get();
                @endphp

                @forelse($packages as $index => $package)
                    <div class="relative flex flex-col p-8 bg-white border rounded-3xl transition-all duration-300 {{ $index === 1 ? 'border-indigo-600 shadow-xl scale-105 z-10' : 'border-slate-200 shadow-sm hover:shadow-lg' }}">
                        @if($index === 1)
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-medium shadow-lg shadow-indigo-500/30">
                                Paling Laris
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-slate-900">{{ $package->package_name }}</h3>
                            <p class="text-sm text-slate-500 mt-2">{{ $package->description }}</p>
                        </div>

                        <div class="mb-8">
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-bold text-slate-900">Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</span>
                                <span class="text-slate-500">/{{ $package->billing_type === 'per_item' ? 'item' : 'kg' }}</span>
                            </div>
                        </div>

                        <ul class="space-y-4 mb-8 flex-1">
                            <li class="flex items-center gap-3 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Cuci + Setrika + Parfum
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Selesai {{ $package->turnaround_hours ?? 48 }} Jam
                            </li>
                            <li class="flex items-center gap-3 text-sm text-slate-600">
                                <svg class="h-5 w-5 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                Gratis Antar Jemput (3km)
                            </li>
                        </ul>

                        <a href="{{ route('order.create') }}" class="block w-full text-center rounded-xl px-6 py-3.5 text-sm font-semibold transition-all {{ $index === 1 ? 'bg-indigo-600 text-white hover:bg-indigo-500 shadow-lg shadow-indigo-500/30' : 'bg-slate-50 text-slate-900 hover:bg-slate-100' }}">
                            Pilih Paket
                        </a>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-slate-500">
                        Belum ada paket yang tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>



    {{-- FAQ Section --}}
    <section class="py-24 bg-white" x-data="{ active: null }">
        <div class="mx-auto max-w-3xl px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">FAQ</h2>
                <p class="mt-4 text-lg text-slate-600">Pertanyaan yang sering diajukan</p>
            </div>

            <div class="space-y-4">
                @foreach([
                    'Apakah ada minimal berat laundry?' => 'Ya, minimal berat untuk layanan antar jemput gratis adalah 3kg. Jika kurang dari itu, akan dikenakan biaya minimum 3kg.',
                    'Berapa lama proses pencucian?' => 'Untuk layanan reguler 48 jam, express 24 jam, dan kilat 6 jam. Waktu dihitung sejak laundry dijemput.',
                    'Apakah deterjennya aman untuk bayi?' => 'Tentu! Kami menggunakan deterjen premium yang hypoallergenic dan ramah lingkungan, aman untuk kulit sensitif dan bayi.',
                    'Bagaimana jika pakaian saya hilang/rusak?' => 'Kami memiliki prosedur quality control yang ketat. Namun jika terjadi hal yang tidak diinginkan, kami memberikan garansi penggantian hingga 10x lipat biaya cuci.',
                ] as $question => $answer)
                    <div class="rounded-2xl bg-slate-50 border border-slate-200 overflow-hidden transition-all duration-200" :class="{ 'ring-2 ring-indigo-600 border-transparent bg-white': active === '{{ $loop->index }}' }">
                        <button @click="active = active === '{{ $loop->index }}' ? null : '{{ $loop->index }}'" class="flex w-full items-center justify-between px-6 py-4 text-left">
                            <span class="font-semibold text-slate-900">{{ $question }}</span>
                            <span class="ml-6 flex h-7 w-7 items-center justify-center rounded-full border border-slate-200 bg-white transition-all" :class="{ 'bg-indigo-600 border-indigo-600 text-white rotate-180': active === '{{ $loop->index }}' }">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="active === '{{ $loop->index }}'" x-collapse class="px-6 pb-4 text-slate-600 text-sm leading-relaxed">
                            {{ $answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-24 bg-slate-900 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&crop=focalpoint&fp-y=.8&w=2830&h=1500&q=80&blend=111827&sat=-100&exp=15&blend-mode=multiply" alt="" class="h-full w-full object-cover object-center opacity-50">
        </div>
        <div class="relative mx-auto max-w-7xl px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Siap Mencoba Laundry Premium?</h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-slate-300">
                Dapatkan diskon 20% untuk pesanan pertama Anda. Gunakan kode <span class="text-indigo-400 font-bold">BARU20</span> saat checkout.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('order.create') }}" class="rounded-full bg-indigo-600 px-8 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 animate-pulse-glow">
                    Pesan Sekarang
                </a>
                <a href="https://wa.me/6285645520620" class="text-sm font-semibold leading-6 text-white flex items-center gap-2 hover:text-indigo-400 transition-colors">
                    Chat WhatsApp <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-200 pt-16 pb-12">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold">O</div>
                        <span class="text-xl font-bold text-slate-900">Omah Susi</span>
                    </div>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Solusi laundry modern untuk gaya hidup sibuk. Cepat, bersih, dan terpercaya.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Layanan</h4>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Cuci Komplit</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Dry Cleaning</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Cuci Sepatu</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Karpet & Bedcover</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Perusahaan</h4>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li><a href="{{ route('about') }}" class="hover:text-indigo-600 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-indigo-600 transition-colors">Lokasi</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Karir</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 mb-6">Kontak</h4>
                    <ul class="space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                            +62 856-4552-0620
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            hello@omahsusi.com
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection