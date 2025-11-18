<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-primary">Kelola Layanan</p>
            <h1 class="text-3xl font-bold text-slate-900">Paket Laundry</h1>
            <p class="text-sm text-slate-500">Tambah, ubah, atau hapus paket yang tampil di halaman guest.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.packages.create') }}" class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                Tambah Paket
            </a>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('message') }}
        </div>
    @endif
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Deskripsi</th>
                        <th class="px-4 py-2">Harga</th>
                        <th class="px-4 py-2">Jenis</th>
                        <th class="px-4 py-2">Durasi (jam)</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $package->package_name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $package->description }}</td>
                            <td class="px-4 py-3 font-semibold">Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $package->billing_type)) }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $package->turnaround_hours }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">
                                <a href="{{ route('admin.packages.edit', $package) }}" class="mr-4 text-primary hover:text-indigo-600">Edit</a>
                                <button type="button" onclick="confirm('Hapus paket {{ $package->package_name }}?') || event.stopImmediatePropagation()" wire:click="delete({{ $package->id }})" class="text-red-500 hover:text-red-600">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada paket yang terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
