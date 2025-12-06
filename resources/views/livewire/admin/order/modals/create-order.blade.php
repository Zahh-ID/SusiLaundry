<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-3 py-5">
            <div
                class="relative w-full max-w-full sm:max-w-4xl h-[88vh] max-h-[90vh] overflow-hidden rounded-2xl sm:rounded-3xl bg-white shadow-2xl animate-fade-in-up">
                <button type="button"
                    class="absolute right-6 top-6 z-10 inline-flex items-center gap-1 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow hover:border-primary hover:text-primary"
                    wire:click="close">
                    ✕ <span>Tutup</span>
                </button>
                <div class="h-full overflow-y-auto p-4 sm:p-5 lg:p-6">
                    <livewire:admin.order.create :embedded="true" wire:key="create-order-child" />
                </div>
            </div>
        </div>
        <style>
            body {
                overflow: hidden;
            }
        </style>
    @endif
</div>