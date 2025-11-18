<div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50 p-4 text-sm text-slate-600">
    <p class="text-xs uppercase text-slate-400">Kode</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $order->order_code }}</p>
    <p class="text-xs uppercase text-slate-400">Pelanggan</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $order->customer?->name ?? 'Tanpa Nama' }}</p>
    <p class="text-xs uppercase text-slate-400">Paket</p>
    <p class="mb-2 font-semibold text-slate-900">{{ ($order->package?->package_name ?? '-') .' • '. ucfirst($order->service_type) }}</p>
    <p class="text-xs uppercase text-slate-400">Berat</p>
    <p class="mb-2 font-semibold text-slate-900">{{ number_format($order->actual_weight ?? $order->estimated_weight ?? 0, 1) }} kg</p>
    <p class="text-xs uppercase text-slate-400">Total</p>
    <p class="mb-2 font-semibold text-slate-900">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</p>
    <p class="text-xs uppercase text-slate-400">Status berikutnya</p>
    <p class="mb-2 font-semibold text-slate-900">{{ $nextStatus }}</p>
</div>
