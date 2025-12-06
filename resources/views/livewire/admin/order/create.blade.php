@php
    $wrapperClass = $embedded ? 'w-full' : 'mx-auto w-full max-w-3xl space-y-4';
    $cardClasses = $embedded ? '' : 'rounded-2xl border border-slate-100 bg-white p-5 shadow-lg';
@endphp

<div class="{{ $wrapperClass }}">
    @if(!$embedded)
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-primary">
            ← Kembali ke daftar pesanan
        </a>
    @endif

    <div class="{{ $cardClasses }}">
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

        @if($showQrisModal)
            {{-- Inline QRIS View (Swaps content) --}}
            <div class="animate-fade-in text-center py-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-900">Scan QRIS</h2>
                    <p class="mt-2 text-slate-600">Silakan scan kode QR di bawah ini untuk menyelesaikan pembayaran.</p>
                </div>
                
                <div class="flex justify-center mb-8">
                    @if(!empty($pendingQrisPayload['qris_image_url']))
                        <div class="p-4 bg-white rounded-2xl shadow-sm border border-slate-200">
                             <img src="{{ $pendingQrisPayload['qris_image_url'] }}" alt="QRIS Code" class="h-64 w-64 rounded-lg object-contain">
                        </div>
                    @else
                        <div class="flex h-64 w-64 items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 text-slate-400 font-medium">
                            QR Code Error
                        </div>
                    @endif
                </div>

                <div class="max-w-md mx-auto space-y-3">
                    <button wire:click="checkPendingPaymentStatus" type="button"
                        class="w-full rounded-xl border border-primary bg-primary px-4 py-3 font-bold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                        Cek Status Pembayaran
                    </button>
                    <button wire:click="cancelPendingQris" type="button"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-bold text-slate-600 hover:border-rose-200 hover:text-rose-600 hover:bg-rose-50 transition-all">
                        Batalkan Transaksi
                    </button>
                </div>
            </div>
        @else
            {{-- Main Creation Form --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-slate-900">Input Pesanan Offline</h1>
                <p class="mt-2 text-slate-600">Buat pesanan baru untuk pelanggan walk-in</p>
            </div>

            {{-- Progress Steps --}}
            <div class="mb-10">
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

            <form wire:submit.prevent="save">
                
                {{-- Step 1: Layanan --}}
                @if($step === 1)
                    <div class="space-y-8 animate-fade-in-up">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Pilih Paket Laundry</h2>
                            <p class="text-sm text-slate-500">Pilih paket yang sesuai untuk pelanggan</p>
                            
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
                                <label class="block text-sm font-semibold text-slate-700 mb-3">Berat Aktual (kg/item)</label>
                                <div class="relative">
                                    <input type="number" step="0.1" wire:model.live="actual_weight" placeholder="0"
                                        class="w-full rounded-xl border-slate-200 px-4 py-3 text-lg font-bold text-slate-900 focus:border-primary focus:ring-primary">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">Kg</div>
                                </div>
                                @error('actual_weight') <span class="mt-1 text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 2: Detail --}}
                @if($step === 2)
                    <div class="space-y-6 animate-fade-in-up">
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Pelanggan</label>
                                <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Nama Lengkap">
                                @error('name') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email (Opsional)</label>
                                <input type="email" wire:model="email" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="email@contoh.com">
                                @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat</label>
                            <textarea wire:model="address" rows="3" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Alamat Lengkap"></textarea>
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
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan (Opsional)</label>
                                <input type="text" wire:model="notes" class="w-full rounded-xl border-slate-200 focus:border-primary focus:ring-primary" placeholder="Catatan khusus...">
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
                                    <span class="text-slate-500">Berat Aktual</span>
                                    <span class="font-semibold text-slate-900">{{ $actual_weight }} Kg</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Pengiriman</span>
                                    <span class="font-semibold text-slate-900 capitalize">{{ $pickup_or_delivery === 'none' ? 'Mandiri' : $pickup_or_delivery }}</span>
                                </div>
                                
                                <div class="border-t border-slate-200 my-3 pt-3 flex justify-between items-center">
                                    <span class="font-bold text-slate-700">Total Tagihan</span>
                                    <span class="text-xl font-bold text-primary">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-3">Metode Pembayaran</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="cursor-pointer rounded-xl border-2 p-4 transition-all hover:bg-slate-50
                                    {{ $payment_method === 'cash' ? 'border-primary bg-primary/5' : 'border-slate-200' }}"
                                    wire:click="$set('payment_method', 'cash')">
                                    <div class="font-bold text-slate-900">Tunai (Cash)</div>
                                    <div class="text-xs text-slate-500">Bayar di kasir</div>
                                </div>

                                <div class="cursor-pointer rounded-xl border-2 p-4 transition-all hover:bg-slate-50
                                    {{ $payment_method === 'qris' ? 'border-primary bg-primary/5' : 'border-slate-200' }}"
                                    wire:click="$set('payment_method', 'qris')">
                                    <div class="font-bold text-slate-900">QRIS</div>
                                    <div class="text-xs text-slate-500">Generate QR Code</div>
                                </div>
                            </div>
                            @error('payment_method') <span class="text-sm text-red-500">{{ $message }}</span> @enderror

                            @if($payment_method === 'cash')
                                <div class="mt-4 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    <input type="checkbox" wire:model="markAsPaid" id="markAsPaid" class="h-5 w-5 rounded border-slate-300 text-primary focus:ring-primary">
                                    <label for="markAsPaid" class="text-sm font-semibold text-slate-700 cursor-pointer select-none">
                                        Pembayaran diterima? (Tandai Lunas)
                                    </label>
                                </div>
                            @endif
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
                        <div></div>
                    @endif

                    @if($step < 3)
                        <button type="button" wire:click="nextStep"
                            class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                            Lanjut
                        </button>
                    @else
                        <button type="submit"
                            class="rounded-xl bg-primary px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all hover:scale-[1.02]">
                            Buat Pesanan
                        </button>
                    @endif
                </div>
            </form>
        @endif
    </div>
</div>
