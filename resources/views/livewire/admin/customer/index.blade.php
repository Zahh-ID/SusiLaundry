<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold text-primary">Database Pelanggan</p>
        <h1 class="text-3xl font-bold text-slate-900">Daftar Pelanggan</h1>
        <p class="text-sm text-slate-500">Pantau kontak pelanggan, alamat, dan total transaksi.</p>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <div class="mb-4">
            <input type="text" placeholder="Cari nama atau email pelanggan" wire:model.debounce.500ms="search"
                   class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Alamat</th>
                        <th class="px-4 py-2">Total Order</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr class="border-t border-slate-100">
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $customer->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-600">{{ $customer->email ?? $customer->phone ?? '—' }}</td>
                            <td class="px-4 py-3 text-sm text-slate-500">{{ $customer->address }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $customer->orders_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
    </div>
</div>
