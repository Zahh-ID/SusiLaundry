<div class="mx-auto w-full max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    {{-- Header --}}
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl">Buat Pesanan Baru</h1>
        <p class="mt-2 text-slate-600">Lengkapi data dalam 3 langkah mudah</p>
    </div>

    {{-- Progress Steps --}}
    <div class="mb-12">
        <div class="relative flex justify-between">
            <div class="absolute left-0 top-1/2 -z-10 h-1 w-full -translate-y-1/2 bg-slate-100 rounded-full"></div>
            <div class="absolute left-0 top-1/2 -z-10 h-1 -translate-y-1/2 bg-primary transition-all duration-500 rounded-full"
                 style="width: {{ ($step - 1) * 50 }}%"></div>

            @foreach(['Layanan', 'Detail', 'Konfirmasi'] as $index => $label)
                @php $stepNum = $index + 1; @endphp
                <div class="flex flex-col items-center gap-2 bg-white px-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border-2 transition-all duration-300
                        {{ $step >= $stepNum ? 'border-primary bg-primary text-white' : 'border-slate-200 bg-white text-slate-400' }}">
                        @if($step > $stepNum)
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <span class="font-bold">{{ $stepNum }}</span>
                        @endif
                    </div>
                    <span class="text-xs font-semibold {{ $step >= $stepNum ? 'text-primary' : 'text-slate-400' }}">{{ $label }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 sm:p-10">
        <form wire:submit.prevent="save">
            
            {{-- Step 1: Layanan --}}
            @if($step === 1)
                <div class="space-y-8 animate-fade-in-up">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Pilih Paket Laundry</h2>
                        <p class="text-sm text-slate-500">Sesuaikan dengan kebutuhan pakaianmu</p>
                        
                        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($packages as $package)
                                <div class="relative cursor-pointer rounded-2xl border-2 p-4 transition-all hover:border-primary/50
                                    {{ $package_id == $package->id ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-slate-100 bg-white' }}"
                                    wire:click="setPackage({{ $package->id }})">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $package->package_name }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $package->turnaround_hours }} Jam Kerja</p>
                                        </div>
                                        @if($package_id == $package->id)
                                            <div class="h-5 w-5 rounded-full bg-primary text-white flex items-center justify-center">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mt-4 text-lg font-bold text-primary">
                                        Rp {{ number_format($package->price_per_kg, 0, ',', '.') }}
                                        <span class="text-xs font-normal text-slate-500">/{{ $package->billing_type === 'per_item' ? 'item' : 'kg' }}</span>
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        @error('package_id') <span class="mt-2 text-sm text-red-500 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-8 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Estimasi Berat (kg/item)</label>
                            <div class="relative">
                                <input type="number" step="0.5" wire:model.live="estimated_weight" placeholder="0"
                                    class="w-full rounded-xl border-slate-200 px-4 py-3 text-lg font-bold text-slate-900 focus:border-primary focus:ring-primary">
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">Kg</div>
                            </div>
                            @error('estimated_weight') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>


                    </div>
                </div>
            @endif

            {{-- Step 2: Detail --}}
            @if($step === 2)
                <div class="space-y-6 animate-fade-in-up">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Nama Anda">
                            @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                            <input type="email" wire:model="email" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="email@contoh.com">
                            @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea wire:model="address" rows="3" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Jalan, Nomor Rumah, Patokan..."></textarea>
                        @error('address') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Opsi Pengiriman</label>
                            <select wire:model.live="pickup_or_delivery" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary">
                                <option value="none">Antar Sendiri ke Outlet</option>
                                <option value="pickup">Jemput Saja</option>
                                <option value="delivery">Antar Jemput (Lengkap)</option>
                            </select>
                            @if($pickup_or_delivery === 'delivery')
                                <p class="mt-1 text-xs text-primary font-medium">+ Rp 10.000 biaya antar</p>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Khusus (Opsional)</label>
                            <input type="text" wire:model="notes" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Misal: Jangan dicampur...">
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 3: Review --}}
            @if($step === 3)
                <div class="animate-fade-in-up">
                    <div class="rounded-2xl bg-slate-50 p-6 border border-slate-100">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Paket Laundry</span>
                                <span class="font-semibold text-slate-900">{{ $packages->find($package_id)->package_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Estimasi Berat</span>
                                <span class="font-semibold text-slate-900">{{ $estimated_weight }} Kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pengiriman</span>
                                <span class="font-semibold text-slate-900 capitalize">{{ $pickup_or_delivery === 'none' ? 'Mandiri' : $pickup_or_delivery }}</span>
                            </div>
                            
                            <div class="border-t border-slate-200 my-3 pt-3 flex justify-between items-center">
                                <span class="font-bold text-slate-700">Total Estimasi</span>
                                <span class="text-xl font-bold text-primary">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="cursor-pointer relative" wire:click="setPaymentMethod('cash')">
                                <div class="rounded-xl border-2 p-4 transition-all {{ $payment_method === 'cash' ? 'border-primary bg-primary/5' : 'border-slate-200 hover:bg-slate-50' }}">
                                    <div class="font-bold text-slate-900">Tunai (Cash)</div>
                                    <div class="text-xs text-slate-500">Bayar saat selesai/antar</div>
                                </div>
                                @if($payment_method === 'cash')
                                    <div class="absolute top-4 right-4 text-primary">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="cursor-pointer relative" wire:click="setPaymentMethod('qris')">
                                <div class="rounded-xl border-2 p-4 transition-all {{ $payment_method === 'qris' ? 'border-primary bg-primary/5' : 'border-slate-200 hover:bg-slate-50' }}">
                                    <div class="font-bold text-slate-900">QRIS</div>
                                    <div class="text-xs text-slate-500">Scan barcode instan</div>
                                </div>
                                @if($payment_method === 'qris')
                                    <div class="absolute top-4 right-4 text-primary">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('payment_method') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
            @endif

            {{-- Navigation Buttons --}}
            <div class="mt-10 flex justify-between pt-6 border-t border-slate-100">
                @if($step > 1)
                    <button type="button" wire:click="prevStep"
                        class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Kembali
                    </button>
                @else
                    <div></div> {{-- Spacer --}}
                @endif

                @if($step < 3)
                    <button type="button" wire:click="nextStep"
                        class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                        Lanjut
                    </button>
                @else
                    <button type="submit"
                        class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                        Kirim Pesanan
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>
