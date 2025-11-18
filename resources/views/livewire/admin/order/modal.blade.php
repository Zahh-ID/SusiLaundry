<div>
    @if($show)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
            <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                        <h2 class="text-2xl font-bold text-slate-900">{{ $title }}</h2>
                    </div>
                    <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" wire:click="closeModal">Tutup</button>
                </div>
                <div class="mt-4 text-sm text-slate-600">
                    {!! $content !!}
                </div>
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    @foreach($buttons as $button)
                        <button type="button" class="{{ $button['class'] }}" wire:click="handleAction('{{ $button['action'] }}')">
                            {{ $button['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
