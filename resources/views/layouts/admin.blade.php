<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin - Susi Laundry' }}</title>

        <link rel="icon" href="{{ asset('logo.ico') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-white text-slate-900">
        <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
            <aside class="border-b border-slate-200 bg-white lg:min-h-screen lg:border-b-0 lg:border-r">
                <div class="flex items-center justify-between px-6 py-5 lg:block">
                    <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">Susi Laundry</a>
                    <span class="text-xs font-semibold text-primary lg:hidden">Admin</span>
                </div>
                <nav class="flex gap-2 px-4 pb-6 pt-2 lg:flex-col">
                    @php
                    $items = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                        ['label' => 'Transaksi', 'route' => 'admin.orders.index'],
                        ['label' => 'Paket Laundry', 'route' => 'admin.packages.index'],
                        ['label' => 'Pelanggan', 'route' => 'admin.customers.index'],
                        ['label' => 'Laporan', 'route' => 'admin.reports.index'],
                    ];
                    @endphp
                    @foreach ($items as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-primary/10 {{ request()->routeIs($item['route'].'*') ? 'bg-primary/10 text-primary' : 'text-slate-600' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>
            <div class="flex min-h-screen flex-col">
                <header class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 py-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Halo, {{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
                            Keluar
                        </button>
                    </form>
                </header>
                <main class="flex-1 bg-white px-6 py-8">
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        </div>

        @livewireScripts
        @livewire('admin.order.modal')
    </body>
</html>
