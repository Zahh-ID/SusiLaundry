<div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600">
    <p class="text-xs uppercase text-slate-400">Kode</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $order->order_code }}</p>
    <p class="text-xs uppercase text-slate-400">Pelanggan</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $order->customer?->name ?? 'Tanpa Nama' }}</p>
    <p class="text-xs uppercase text-slate-400">Status saat ini</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $status }}</p>
</div>
