<div class="space-y-6">
    <a href="{{ route('admin.orders.index') }}"
        class="inline-flex items-center text-sm font-semibold text-slate-600 hover:text-primary">
        ← Kembali ke daftar
    </a>
    <div>
        <p class="text-sm font-semibold text-primary">Perbarui Pesanan</p>
        <h1 class="text-3xl font-bold text-slate-900">Update Pesanan {{ $order->order_code }}</h1>
        <p class="text-sm text-slate-500">Perbarui status layanan, berat, pembayaran, dan catatan operasional.</p>
    </div>
    @if (session()->has('message'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-4 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:col-span-1">
            <h2 class="text-xl font-semibold text-slate-900">Informasi Pelanggan</h2>
            <div class="space-y-3 text-sm text-slate-600">
                <div>
                    <p class="text-xs uppercase text-slate-400">Nama</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $order->customer?->name }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-400">Email</p>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ $order->customer?->email ?? $order->customer?->phone ?? 'Belum ada' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-400">Alamat</p>
                    <p class="text-slate-600">{{ $order->customer?->address }}</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <p class="text-xs uppercase text-slate-400">Paket</p>
                        <p class="text-lg font-semibold text-slate-900">{{ $order->package?->package_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs uppercase text-slate-400">Layanan</p>
                        <p class="text-lg font-semibold capitalize text-slate-900">{{ $order->service_type }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs uppercase text-slate-400">Antrian</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $order->queue_position ?? '-' }}</p>
                </div>
            </div>
            <div class="mt-6">
                <p class="text-xs uppercase text-slate-400">Activity Log</p>
                <ul class="mt-3 max-h-64 space-y-2 overflow-y-auto text-xs text-slate-600">
                    @forelse(array_reverse($order->activity_log ?? []) as $log)
                        <li class="rounded-xl border border-slate-100 px-3 py-2">
                            <p class="font-semibold text-slate-900">{{ ucfirst($log['actor']) }} •
                                {{ str_replace('_', ' ', $log['action'] ?? '') }}
                            </p>
                            <p>{{ \Carbon\Carbon::parse($log['timestamp'])->diffForHumans() }}</p>
                        </li>
                    @empty
                        <li class="text-slate-400">Belum ada aktivitas.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="space-y-6 rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:col-span-2">
            <form class="space-y-4" wire:submit.prevent="update">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Status Pesanan</label>
                        <select wire:model="status"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach($availableStatuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Estimasi Selesai</label>
                        <input type="datetime-local" wire:model="estimated_completion"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Berat Aktual (kg)</label>
                        <input type="number" step="0.1" wire:model="actual_weight"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('actual_weight') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Harga / kg</label>
                        <input type="number" step="0.1" wire:model="price_per_kg"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('price_per_kg') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Total Biaya</label>
                        <input type="number" step="0.1" wire:model="total_price"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                        @error('total_price') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Metode Pembayaran</label>
                        <select wire:model="payment_method"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach($paymentMethods as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Status Pembayaran</label>
                        <select wire:model="payment_status"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach($paymentStatuses as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Pickup / Delivery</label>
                        <select wire:model="pickup_or_delivery"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                            @foreach($pickupOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Biaya Delivery</label>
                        <input type="number" step="0.1" wire:model="delivery_fee"
                            class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>
                </div>
                <button type="submit"
                    class="w-full rounded-2xl border border-primary bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                    Simpan Perubahan
                </button>
            </form>

            <div class="mt-8 space-y-4">
                <h3 class="text-lg font-semibold text-slate-900">Pembayaran</h3>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600">
                    <p>Metode: <span class="font-semibold text-slate-900">{{ ucfirst($order->payment_method) }}</span>
                    </p>
                    <p>Status: <span class="font-semibold text-slate-900">{{ $order->payment_status_label }}</span></p>
                    @if($latestPayment && $order->payment_method === 'qris')
                        <div class="mt-4 flex flex-col items-center gap-2">
                            <img src="{{ $latestPayment->qris_image_url }}" alt="QRIS"
                                class="h-40 w-40 rounded-xl border border-slate-200">
                            <p class="text-xs text-slate-500">Kedaluwarsa:
                                {{ optional($latestPayment->expiry_time)->translatedFormat('d M Y H:i') }}
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <button wire:click="regenerateQris" type="button"
                                    class="rounded-full border border-primary px-4 py-2 text-sm font-semibold text-primary hover:bg-primary/10">
                                    Regenerasi QRIS
                                </button>
                                <button wire:click="markPaymentPaid" type="button"
                                    class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                                    Tandai Lunas
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 flex gap-3">
                            <button wire:click="markPaymentPaid" type="button"
                                class="rounded-full border border-primary bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-600">
                                Tandai Lunas
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Unpaid Order Modal --}}
    <div x-data="{ open: @entangle('showUnpaidModal') }" x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 py-6" x-transition.opacity>
        <div class="w-full max-w-md rounded-3xl border border-slate-100 bg-white p-6 text-center shadow-2xl"
            x-show="open" x-transition.scale @click.away="open = false">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="mt-4 text-xl font-bold text-slate-900">Pesanan Belum Lunas</h2>
            <p class="mt-2 text-sm text-slate-600">
                Status tidak dapat diubah menjadi <strong>Diambil</strong> karena pembayaran belum selesai. Harap tandai
                lunas terlebih dahulu.
            </p>
            <div class="mt-6">
                <button type="button" @click="open = false"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 hover:bg-slate-50">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>