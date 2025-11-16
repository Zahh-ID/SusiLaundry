<div class="mx-auto grid w-full max-w-6xl gap-12 px-6 py-16 lg:grid-cols-2">
    <div>
        <p class="text-sm font-semibold text-primary">Form Pemesanan</p>
        <h1 class="mb-3 text-4xl font-bold text-slate-900">Laundry kamu kami urus</h1>
        <p class="text-slate-600">
            Isi data dengan benar dan tim kami akan menghubungi maksimal 5 menit setelah form dikirim.
        </p>
        <div class="mt-10 rounded-3xl border border-slate-100 bg-blue-50/60 p-6 text-sm text-slate-600">
            <p class="font-semibold text-slate-900">Panduan cepat:</p>
            <ul class="mt-4 list-disc space-y-1 pl-5">
                <li>Minimal order 3 kg untuk layanan antar jemput.</li>
                <li>Berat aktual dan total biaya akan dikonfirmasi setelah cucian ditimbang.</li>
                <li>Kode tracking otomatis dikirim via WhatsApp.</li>
            </ul>
        </div>
    </div>
    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-soft">
        @if(session()->has('error'))
            <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
                {{ session('error') }}
            </div>
        @endif
        <form class="space-y-5" wire:submit.prevent="save">
            <div>
                <label for="name" class="text-sm font-semibold text-slate-600">Nama Lengkap</label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Contoh: Ayu Lestari"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="phone" class="text-sm font-semibold text-slate-600">Nomor WhatsApp</label>
                <input type="text" id="phone" wire:model.defer="phone" placeholder="0812xxxx"
                       class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('phone') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="address" class="text-sm font-semibold text-slate-600">Alamat Penjemputan</label>
                <textarea id="address" wire:model.defer="address" rows="3" placeholder="Tulis alamat lengkap"
                          class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                @error('address') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="package_id" class="text-sm font-semibold text-slate-600">Paket Laundry</label>
                <select id="package_id" wire:model.defer="package_id"
                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="">Pilih paket</option>
                    @foreach($packages as $package)
                        <option value="{{ $package->id }}">
                            {{ $package->package_name }} — Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}
                            @if($package->billing_type === 'per_item')
                                /item
                            @elseif($package->billing_type === 'paket')
                                /paket
                            @else
                                /kg
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('package_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="estimated_weight" class="text-sm font-semibold text-slate-600">Estimasi Berat (kg)</label>
                    <input type="number" step="0.5" id="estimated_weight" wire:model.defer="estimated_weight" placeholder="Misal 5"
                           class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    @error('estimated_weight') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="service_type" class="text-sm font-semibold text-slate-600">Jenis Layanan</label>
                    <select id="service_type" wire:model.defer="service_type"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <option value="regular">Regular (48 jam)</option>
                        <option value="express">Express (24 jam)</option>
                        <option value="kilat">Kilat (6 jam)</option>
                    </select>
                    @error('service_type') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-600">Metode Pembayaran</p>
                <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                    @foreach($paymentMethods as $key => $label)
                        <label class="flex flex-1 items-center gap-2 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary">
                            <input type="radio" class="text-primary focus:ring-primary" wire:model.defer="payment_method" value="{{ $key }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('payment_method') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                <p class="mt-2 text-xs text-slate-500">QRIS otomatis dikirim setelah form terkirim. Pembayaran cash dilakukan saat kurir menjemput.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="pickup_or_delivery" class="text-sm font-semibold text-slate-600">Pickup / Delivery</label>
                    <select id="pickup_or_delivery" wire:model.defer="pickup_or_delivery"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @foreach($pickupOptions as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('pickup_or_delivery') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div class="rounded-2xl border border-dashed border-slate-200 p-4 text-xs text-slate-500">
                    Pickup & delivery tersedia gratis untuk area dalam kota dengan minimal 3kg. Pilih "Antar ke Pelanggan" jika ingin pesanan dikirim kembali.
                </div>
            </div>
            <div>
                <label for="notes" class="text-sm font-semibold text-slate-600">Catatan Tambahan</label>
                <textarea id="notes" wire:model.defer="notes" rows="3" placeholder="Contoh: tolong pisahkan pakaian putih"
                          class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                @error('notes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="flex flex-col gap-3 md:flex-row">
                <button type="submit" class="flex-1 rounded-2xl bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                    Kirim Pemesanan
                </button>
                <button type="button" wire:click="resetForm" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 font-semibold text-slate-700 hover:border-primary hover:text-primary">
                    Reset Form
                </button>
                <a href="{{ route('landing') }}" class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
