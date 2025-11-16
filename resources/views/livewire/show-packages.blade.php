<div class="mx-auto w-full max-w-6xl px-6 py-16">
    <div class="mb-10 text-center">
        <p class="text-sm font-semibold text-primary">Daftar Paket Laundry</p>
        <h1 class="text-3xl font-bold text-slate-900">Pilih paket sesuai kebutuhanmu</h1>
        <p class="text-slate-600">Transparan soal harga, sudah termasuk deterjen premium dan setrika rapi.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @foreach($packages as $package)
            <div class="flex flex-col rounded-3xl border border-slate-100 bg-white/90 p-6 shadow-soft">
                <div class="flex items-start justify-between">
                    <div>
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
                    </div>
                    <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                        Estimasi {{ ceil(($package->turnaround_hours ?? 48) / 24) }} hari
                    </span>
                </div>
                <p class="mt-4 flex-1 text-sm text-slate-600">{{ $package->description }}</p>
                <div class="mt-5 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span class="rounded-full bg-slate-100 px-3 py-1">Pewangi premium</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1">Setrika rapi</span>
                    <span class="rounded-full bg-slate-100 px-3 py-1">Quality control</span>
                </div>
                <a href="{{ route('order.create') }}" class="mt-6 w-full rounded-2xl bg-primary px-4 py-2 text-center text-white">
                    Pesan Paket Ini
                </a>
                <a href="{{ route('contact') }}" class="mt-3 w-full rounded-2xl border border-slate-200 px-4 py-2 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
                    Hubungi Admin
                </a>
            </div>
        @endforeach
    </div>
    <div class="mt-8 flex flex-col gap-3 text-center sm:flex-row sm:justify-center">
        <a href="{{ route('landing') }}" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
            Kembali
        </a>
        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="rounded-2xl border border-primary px-5 py-3 text-sm font-semibold text-primary hover:bg-primary/10">
            Chat WhatsApp Admin
        </a>
    </div>
</div>
