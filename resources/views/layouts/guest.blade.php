<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('logo.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased bg-white">
    <div class="min-h-screen grid lg:grid-cols-2">
        <!-- Left Side: Branding (Premium Dark Split) -->
        <div class="hidden lg:flex flex-col justify-center items-center bg-slate-900 relative overflow-hidden">
            <!-- Subtle Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 opacity-50"></div>

            <!-- Abstract Pattern -->
            <div class="absolute inset-0 opacity-10"
                style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;">
            </div>

            <div class="relative z-10 text-center max-w-md px-12">
                <div class="mb-8 flex justify-center">
                    <div class="p-4 bg-white/5 backdrop-blur-sm rounded-3xl ring-1 ring-white/10 shadow-2xl">
                        <img src="{{ asset('logo.ico') }}" alt="Omah Susi Logo" class="h-24 w-24 drop-shadow-lg">
                    </div>
                </div>

                <h1 class="text-4xl font-bold mb-6 text-white tracking-tight">
                    Omah Susi <span class="text-primary">Laundry</span>
                </h1>

                <p class="text-lg text-slate-300 leading-relaxed font-light">
                    "Experience the premium care your clothes deserve. Fast, fresh, and flawlessly clean."
                </p>

                <!-- Trust Badges -->
                <div class="mt-12 flex items-center justify-center gap-6">
                    <div
                        class="px-4 py-2 rounded-full bg-white/5 ring-1 ring-white/10 text-slate-300 text-sm font-medium">
                        ✨ Premium Care
                    </div>
                    <div
                        class="px-4 py-2 rounded-full bg-white/5 ring-1 ring-white/10 text-slate-300 text-sm font-medium">
                        🚀 Express Service
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="absolute bottom-8 text-slate-500 text-xs">
                &copy; {{ date('Y') }} Omah Susi Laundry
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="flex flex-col justify-center items-center p-6 lg:p-12 bg-white">
            <div class="w-full max-w-md space-y-8">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="/" wire:navigate class="flex flex-col items-center gap-2">
                        <img src="{{ asset('logo.ico') }}" alt="Omah Susi Logo" class="h-12 w-12 rounded-xl shadow-md">
                        <span class="text-2xl font-bold text-slate-900">Omah Susi</span>
                    </a>
                </div>

                {{ $slot }}
            </div>

            <div class="mt-8 text-center text-xs text-slate-400 lg:hidden">
                &copy; {{ date('Y') }} Omah Susi Laundry. All rights reserved.
            </div>
        </div>
    </div>
</body>

</html>