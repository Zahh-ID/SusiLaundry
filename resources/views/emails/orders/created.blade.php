<x-mail::message>
# Pesanan Kamu Kami Terima

Halo {{ $customer->name ?? 'Pelanggan' }},

Terima kasih telah mempercayakan cucianmu ke {{ config('app.name') }}. Berikut ringkasan pesanan **{{ $order->order_code }}**:

- Paket: {{ $order->package?->package_name ?? '-' }} ({{ ucfirst($order->service_type) }})
- Estimasi Berat: {{ $order->estimated_weight }} kg
- Status awal: {{ $order->status_label }}
- Estimasi selesai: {{ optional($order->estimated_completion)->translatedFormat('d M Y H:i') ?? 'menunggu konfirmasi' }}

Kamu dapat memantau progres dan pembayaran kapan saja melalui tautan berikut.

<x-mail::button :url="route('tracking', ['code' => $order->order_code])">
Lihat Status Pesanan
</x-mail::button>

Jika ada catatan tambahan, cukup balas email ini. Kami akan segera menindaklanjuti.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
