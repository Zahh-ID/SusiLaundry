<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl animate-fade-in-up transition-all">

                @if($showPaymentAlert)
                    <div class="animate-fade-in text-center py-6">
                        @if(str_contains(strtolower($summary['Pembayaran'] ?? ''), 'qris'))
                            {{-- QRIS Alert UI --}}
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-slate-900">Pembayaran QRIS</h2>
                                <p class="mt-2 text-slate-600">Pelanggan belum melakukan pembayaran.</p>
                            </div>

                            <div class="flex flex-col items-center justify-center mb-6 gap-4">
                                @if(!empty($qrisData['qris_image_url']))
                                    <div class="p-4 bg-white rounded-2xl shadow-sm border border-slate-200 relative">
                                        <img src="{{ $qrisData['qris_image_url'] }}" alt="QRIS Code"
                                            class="h-56 w-56 rounded-lg object-contain">
                                        @if(isset($qrisData['expiry_time']) && \Carbon\Carbon::parse($qrisData['expiry_time'])->isPast())
                                            <div class="absolute inset-0 bg-white/90 flex items-center justify-center rounded-2xl">
                                                <span class="text-rose-600 font-bold">Expired</span>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">
                                        Exp:
                                        {{ isset($qrisData['expiry_time']) ? \Carbon\Carbon::parse($qrisData['expiry_time'])->format('d M Y H:i') : '-' }}
                                    </p>
                                @else
                                    <div
                                        class="flex h-56 w-56 items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 text-slate-400 font-medium">
                                        QR Code Belum Tersedia
                                    </div>
                                @endif
                            </div>

                            <div class="grid gap-3">
                                <button wire:click="regenerateQris"
                                    class="w-full rounded-xl border-2 border-primary bg-white px-4 py-3 font-bold text-primary hover:bg-primary hover:text-white transition-all">
                                    🔄 Generate QRIS Baru
                                </button>

                                <button wire:click="skipPaymentAndSave"
                                    class="w-full rounded-xl bg-primary px-4 py-3 font-bold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all">
                                    Cek Status / Lanjut
                                </button>

                                <button wire:click="$set('showPaymentAlert', false)"
                                    class="w-full rounded-xl border-2 border-slate-100 bg-slate-50 px-4 py-3 font-bold text-slate-600 hover:bg-slate-100 transition-all">
                                    Kembali
                                </button>
                            </div>

                        @else
                            {{-- Cash Alert UI (Existing) --}}
                            <div class="mb-6">
                                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 mb-4">
                                    <svg class="h-8 w-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-slate-900">Pembayaran Belum Diterima</h2>
                                <p class="mt-2 text-slate-600">Pesanan ini menggunakan metode <span
                                        class="font-bold text-slate-900">Tunai</span> dan belum lunas.</p>
                            </div>

                            <div class="grid gap-3">
                                <button wire:click="confirmPaymentAndSave"
                                    class="w-full rounded-xl bg-primary px-4 py-3 font-bold text-white shadow-lg shadow-primary/25 hover:bg-indigo-600 transition-all">
                                    Konfirmasi Sudah Bayar & Lanjut
                                </button>

                                <button wire:click="skipPaymentAndSave"
                                    class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 font-bold text-slate-700 hover:bg-slate-100 transition-all">
                                    Belum Bayar (Lanjut Saja)
                                </button>

                                <button wire:click="$set('showPaymentAlert', false)"
                                    class="w-full rounded-xl border-2 border-slate-100 bg-slate-50 px-4 py-3 font-bold text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all">
                                    Kembali
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    {{-- Form Content --}}
                    <div>
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                                <h2 class="text-2xl font-bold text-slate-900">Proses Pesanan?</h2>
                            </div>
                            <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary"
                                wire:click="close">Tutup</button>
                        </div>

                        @if(!empty($summary))
                            <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600 space-y-2">
                                @foreach($summary as $label => $value)
                                    <div>
                                        <p class="text-xs uppercase text-slate-400">{{ $label }}</p>
                                        <p class="font-semibold text-slate-900">{{ $value }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($currentStatus === 'pending_confirmation')
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-slate-600" for="actual_weight">Berat Aktual
                                        (kg)</label>
                                    <input id="actual_weight" type="number" step="0.1" wire:model="actual_weight"
                                        class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    @error('actual_weight')
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Payment Checkbox for Cash Orders --}}
                                @if($summary['Pembayaran'] && str_contains(strtolower($summary['Pembayaran']), 'cash'))
                                    <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                        <input type="checkbox" id="markAsPaid" wire:model="markAsPaid"
                                            class="h-5 w-5 rounded border-slate-300 text-primary focus:ring-primary/20">
                                        <label for="markAsPaid" class="text-sm font-semibold text-slate-700">
                                            Sudah dibayar tunai?
                                        </label>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            @if($currentStatus === 'pending_confirmation')
                                <button type="button"
                                    class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
                                    wire:click="saveWithWeight">
                                    Simpan Berat & Lanjut
                                </button>
                            @endif

                            <button type="button"
                                class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
                                wire:click="save">
                                Lanjutkan
                            </button>

                            <button type="button"
                                class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary"
                                wire:click="close">
                                Batal
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>