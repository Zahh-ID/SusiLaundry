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
    <body class="font-sans antialiased bg-slate-900 text-slate-100 dark-theme">
        <div class="min-h-screen flex flex-col">
            <header class="sticky top-0 z-30 border-b border-slate-800 bg-slate-900/90 backdrop-blur">
                <div class="mx-auto flex h-16 w-full max-w-6xl items-center justify-between px-6">
                    <a href="{{ route('landing') }}" class="text-xl font-bold text-primary">Susi Laundry</a>
                    <nav class="hidden items-center gap-6 text-sm font-semibold text-slate-200 md:flex">
                        <a href="{{ route('landing') }}#layanan" class="hover:text-primary">Layanan</a>
                        <a href="{{ route('landing') }}#paket" class="hover:text-primary">Harga</a>
                        <a href="{{ route('promo') }}" class="hover:text-primary">Promo</a>
                        <a href="{{ route('about') }}" class="hover:text-primary">Tentang Kami</a>
                        <a href="{{ route('contact') }}" class="hover:text-primary">Kontak</a>
                        <a href="{{ route('tracking') }}" class="hover:text-primary">Cek Status</a>
                    </nav>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('tracking') }}" class="hidden rounded-full border border-slate-700 px-4 py-2 text-sm font-semibold text-slate-100 hover:border-primary hover:text-primary md:inline-flex">
                            Cek Status
                        </a>
                        <a href="{{ route('order.create') }}" class="rounded-full bg-primary px-5 py-2 text-sm font-semibold text-white shadow-soft hover:bg-indigo-500">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1">
                {{ $slot ?? '' }}
                @yield('content')
            </main>

            <footer class="mt-16 border-t border-slate-800 bg-slate-900">
                <div class="mx-auto flex w-full max-w-6xl flex-col gap-4 px-6 py-10 text-sm text-slate-400 md:flex-row md:items-center md:justify-between">
                    <p>© {{ now()->year }} Omah Susi Laundry. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="mailto:halo@susilaundry.id" class="hover:text-primary">halo@susilaundry.id</a>
                        <a href="tel:+6281234567890" class="hover:text-primary">+62 812-3456-7890</a>
                        @auth
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-primary">Admin Login</a>
                        @endauth
                    </div>
                </div>
            </footer>
        </div>

        @livewireScripts
    </body>
</html>
