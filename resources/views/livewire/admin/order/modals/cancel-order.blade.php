<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl animate-fade-in-up">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                        <h2 class="text-2xl font-bold text-slate-900">Batalkan Pesanan?</h2>
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

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button"
                        class="flex-1 rounded-2xl border border-rose-200 bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700"
                        wire:click="save">
                        Ya, batalkan
                    </button>
                    <button type="button"
                        class="flex-1 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:border-primary hover:text-primary"
                        wire:click="close">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>