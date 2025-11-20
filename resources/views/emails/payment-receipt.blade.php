<x-mail::message>
# Pembayaran Diterima

Halo {{ $customer->name ?? 'Pelanggan' }},

Kami telah menerima pembayaran untuk pesanan **{{ $order->order_code }}**. Berikut ringkasan transaksinya.

<x-mail::panel>
**Tanggal Pembayaran:** {{ optional($payment?->updated_at ?? $order->updated_at)->format('d M Y H:i') }}  
**Metode:** {{ ucfirst($order->payment_method) }}  
**Status:** {{ $order->payment_status_label }}  
**Total Dibayar:** Rp {{ number_format($payment->amount ?? $order->total_price ?? 0, 0, ',', '.') }}
</x-mail::panel>

Detail paket: {{ $order->package?->package_name ?? '-' }} ({{ ucfirst($order->service_type) }}) dengan estimasi berat {{ $order->estimated_weight }} kg.

<x-mail::button :url="route('tracking', ['code' => $order->order_code])">
Lihat Status Pesanan
</x-mail::button>

Jika informasi ini tidak sesuai, balas email ini agar tim kami dapat membantu.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
