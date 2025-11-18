<div class="mx-auto w-full max-w-3xl space-y-6">
    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-primary">
        ← Kembali ke daftar paket
    </a>
    <div>
        <p class="text-sm font-semibold text-primary">Edit Paket</p>
        <h1 class="text-3xl font-bold text-slate-900">Perbarui Paket {{ $package->package_name }}</h1>
        <p class="text-sm text-slate-500">Sesuaikan informasi paket sesuai kebutuhan operasional.</p>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        <form class="space-y-5" wire:submit.prevent="update">
            <div>
                <label for="package_name" class="text-sm font-semibold text-slate-600">Nama Paket</label>
                <input type="text" id="package_name" wire:model.defer="package_name"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('package_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="description" class="text-sm font-semibold text-slate-600">Deskripsi</label>
                <textarea id="description" rows="4" wire:model.defer="description"
                          class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="price_per_kg" class="text-sm font-semibold text-slate-600">Harga per kg</label>
                <input type="number" id="price_per_kg" wire:model.defer="price_per_kg"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('price_per_kg') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="billing_type" class="text-sm font-semibold text-slate-600">Jenis Tagihan</label>
                    <select id="billing_type" wire:model.defer="billing_type"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="per_kg">Per Kg</option>
                        <option value="per_item">Per Item</option>
                        <option value="paket">Paket</option>
                    </select>
                    @error('billing_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="turnaround_hours" class="text-sm font-semibold text-slate-600">Durasi (jam)</label>
                    <input type="number" id="turnaround_hours" wire:model.defer="turnaround_hours"
                           class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('turnaround_hours') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="w-full rounded-2xl border border-primary bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                Update Paket
            </button>
        </form>
    </div>
</div>
