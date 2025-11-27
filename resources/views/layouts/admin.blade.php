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

<body class="font-sans antialiased bg-white text-slate-900" x-data="{ sidebarOpen: false }">
    <!-- Mobile Backdrop -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-40 bg-slate-900/80 lg:hidden"
        @click="sidebarOpen = false"></div>

    <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" @keydown.window.escape="sidebarOpen = false">

            <div class="flex items-center justify-between px-6 py-5">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">Susi Laundry</a>
                <!-- Close Button (Mobile) -->
                <button type="button" class="text-slate-500 hover:text-slate-700 lg:hidden"
                    @click="sidebarOpen = false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col gap-2 px-4 pb-6 pt-2">
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
                    <a href="{{ route($item['route']) }}"
                        class="rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-primary/10 {{ request()->routeIs($item['route'] . '*') ? 'bg-primary/10 text-primary' : 'text-slate-600' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="flex min-h-screen flex-col">
            <header
                class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 bg-white px-6 py-4">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Button (Mobile) -->
                    <button type="button" class="-m-2.5 p-2.5 text-slate-700 lg:hidden"
                        @click="sidebarOpen = !sidebarOpen">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div>
                        <p class="text-sm font-semibold text-slate-700">Halo, {{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
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