<div class="mx-auto max-w-2xl space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Webhook Simulator</h1>
        <p class="text-slate-500">Simulasikan notifikasi pembayaran dari Midtrans.</p>
    </div>

    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-sm">
        <form wire:submit.prevent="simulate" class="space-y-6">
            <div>
                <label class="text-sm font-semibold text-slate-600">Kode Order</label>
                <input type="text" wire:model="order_code" placeholder="Contoh: 8X92JKA2"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                @error('order_code') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="text-sm font-semibold text-slate-600">Status Transaksi</label>
                <select wire:model="status"
                    class="mt-2 w-full rounded-2xl border border-slate-200 px-4 py-3 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="settlement">Settlement (Paid)</option>
                    <option value="pending">Pending</option>
                    <option value="expire">Expire</option>
                    <option value="deny">Deny (Failed)</option>
                    <option value="cancel">Cancel</option>
                </select>
                @error('status') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>

            <button type="submit"
                class="w-full rounded-2xl border border-primary bg-primary px-4 py-3 font-semibold text-white hover:bg-indigo-600">
                Kirim Webhook
            </button>
        </form>

        @if ($response_message)
            <div
                class="mt-6 rounded-2xl border p-4 text-sm font-semibold {{ $response_success ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                {{ $response_message }}
            </div>
        @endif
    </div>
</div>