<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Susi Laundry' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-white text-slate-900">
    <div class="min-h-screen flex flex-col" x-data="{ mobileNav: false }">
        <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-4 sm:px-6">
                <a href="{{ route('landing') }}"
                    class="flex items-center gap-2 text-xl sm:text-2xl font-bold text-primary">
                    <img src="{{ asset('logo.ico') }}" alt="Logo Susi Laundry"
                        class="h-8 w-8 rounded-xl object-contain">
                    <span>Susi Laundry</span>
                </a>
                <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-600 md:flex">
                    <a href="{{ route('landing') }}#layanan" class="hover:text-primary">Layanan</a>
                    <a href="{{ route('landing') }}#paket" class="hover:text-primary">Harga</a>

                    <a href="{{ route('about') }}" class="hover:text-primary">Tentang Kami</a>
                    <a href="{{ route('contact') }}" class="hover:text-primary">Kontak</a>

                </nav>
                <div class="flex items-center gap-3">

                    <a href="{{ route('tracking') }}"
                        class="hidden rounded-full border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary md:inline-flex">
                        Cek Status
                    </a>
                    <a href="{{ route('order.create') }}"
                        class="hidden rounded-full border border-primary bg-primary px-5 py-2 text-sm font-semibold text-white shadow-soft hover:bg-indigo-500 md:inline-flex">
                        Pesan Sekarang
                    </a>
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white p-2 text-slate-700 hover:border-primary hover:text-primary md:hidden"
                        @click="mobileNav = !mobileNav" aria-label="Buka menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                :d="mobileNav ? 'M6 18L18 6M6 6l12 12' : 'M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5'" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="md:hidden" x-show="mobileNav" x-transition.origin.top.left @click.away="mobileNav = false">
                <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 pb-4">
                    <div class="rounded-2xl border border-slate-200 bg-white/95 shadow-sm divide-y divide-slate-100">
                        <div class="flex flex-col">
                            <a href="{{ route('landing') }}#layanan" @click="mobileNav = false"
                                class="px-4 py-3 text-sm font-semibold text-slate-700 hover:text-primary">Layanan</a>
                            <a href="{{ route('landing') }}#paket" @click="mobileNav = false"
                                class="px-4 py-3 text-sm font-semibold text-slate-700 hover:text-primary">Harga</a>

                            <a href="{{ route('about') }}" @click="mobileNav = false"
                                class="px-4 py-3 text-sm font-semibold text-slate-700 hover:text-primary">Tentang
                                Kami</a>
                            <a href="{{ route('contact') }}" @click="mobileNav = false"
                                class="px-4 py-3 text-sm font-semibold text-slate-700 hover:text-primary">Kontak</a>

                        </div>
                        <div class="grid grid-cols-1 gap-3 px-4 py-3">

                            <a href="{{ route('tracking') }}" @click="mobileNav = false"
                                class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
                                Cek Status
                            </a>
                            <a href="{{ route('order.create') }}" @click="mobileNav = false"
                                class="inline-flex items-center justify-center rounded-full border border-primary bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-soft hover:bg-indigo-500">
                                Pesan Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="mt-16 border-t border-slate-200 bg-white">
            <div
                class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-6 py-10 text-sm text-slate-500 md:flex-row md:items-center md:justify-between">
                <p>© {{ now()->year }} Omah Susi Laundry. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="mailto:halo@susilaundry.paulfjr.my.id"
                        class="hover:text-primary text-slate-600">halo@susilaundry.paulfjr.my.id</a>
                    <a href="tel:085645520620" class="hover:text-primary text-slate-600">0856-4552-0620</a>
                    @auth
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-primary text-slate-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-primary text-slate-600">Admin Login</a>
                    @endauth
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>