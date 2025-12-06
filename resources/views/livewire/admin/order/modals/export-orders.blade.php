<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl animate-fade-in-up">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                        <h2 class="text-2xl font-bold text-slate-900">Export CSV?</h2>
                    </div>
                    <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary"
                        wire:click="close">Tutup</button>
                </div>
                <p class="mt-3 text-sm text-slate-600">File akan diunduh berdasarkan filter saat ini.</p>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="button"
                        class="flex-1 rounded-2xl border border-primary bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-600"
                        wire:click="export">
                        Export Sekarang
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