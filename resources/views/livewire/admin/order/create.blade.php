<div class="mx-auto w-full max-w-4xl space-y-6">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-primary">
        ← Kembali ke daftar pesanan
    </a>
    <div>
        <p class="text-sm font-semibold text-primary">Tambah Pesanan Manual</p>
        <h1 class="text-3xl font-bold text-slate-900">Input Pesanan Offline</h1>
        <p class="text-sm text-slate-500">Gunakan form ini untuk pesanan via telepon atau walk-in.</p>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
        @if(session()->has('message') && $successCode)
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('message') }} Kode tracking: {{ $successCode }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                {{ session('error') }}
            </div>
        @endif
        <form class="space-y-5" wire:submit.prevent="save">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-600">Nama Pelanggan</label>
                    <input type="text" wire:model.defer="name" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600">Nomor HP</label>
                    <input type="text" wire:model.defer="phone" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600">Alamat</label>
                <textarea wire:model.defer="address" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-600">Paket</label>
                    <select wire:model.defer="package_id" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="">Pilih paket</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->package_name }} — Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}/kg</option>
                        @endforeach
                    </select>
                    @error('package_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600">Jenis Layanan</label>
                    <select wire:model.defer="service_type" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="regular">Regular (48 jam)</option>
                        <option value="express">Express (24 jam)</option>
                        <option value="kilat">Kilat (6 jam)</option>
                    </select>
                    @error('service_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-600">Estimasi Berat (kg)</label>
                    <input type="number" step="0.5" wire:model.defer="estimated_weight" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('estimated_weight') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600">Status Pesanan</label>
                    <select wire:model.defer="status" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600">Metode Pembayaran</label>
                <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                    @foreach($paymentMethods as $key => $label)
                        <label class="flex flex-1 items-center gap-2 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary">
                            <input type="radio" class="text-primary focus:ring-primary" wire:model.defer="payment_method" value="{{ $key }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('payment_method') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-semibold text-slate-600">Pickup / Delivery</label>
                    <select wire:model.defer="pickup_or_delivery" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($pickupOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('pickup_or_delivery') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-600">Biaya Delivery</label>
                    <input type="number" step="0.1" wire:model.defer="delivery_fee" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" placeholder="Opsional">
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-600">Catatan</label>
                <textarea wire:model.defer="notes" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                Simpan Pesanan
            </button>
        </form>
    </div>
</div>
