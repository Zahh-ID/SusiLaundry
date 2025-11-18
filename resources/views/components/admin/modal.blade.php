@props([
    'show' => false,
    'title' => '',
    'description' => null,
    'closeAction' => 'wire:click=\'closeModal\'',
])

@if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-primary">Konfirmasi</p>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $title }}</h2>
                </div>
                <button type="button" class="text-sm font-semibold text-slate-500 hover:text-primary" {{ $closeAction }}>
                    Tutup
                </button>
            </div>
            @if($description)
                <p class="mt-3 text-sm text-slate-600">{{ $description }}</p>
            @endif
            <div class="mt-4 space-y-3 text-sm text-slate-600">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
