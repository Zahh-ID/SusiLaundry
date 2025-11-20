<x-mail::message>
# Ada Update Pesananmu

Halo {{ $customer->name ?? 'Pelanggan' }},

Kami baru saja memperbarui pesanan **{{ $order->order_code }}**.

- Status terbaru: **{{ $order->status_label }}**
- Status pembayaran: **{{ $order->payment_status_label }}**
- Total saat ini: Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}

@if(!empty($additionalMessage))
> {{ $additionalMessage }}
@endif

Klik tombol berikut untuk melihat detail lengkap dan QR pembayaran (jika ada).

<x-mail::button :url="route('tracking', ['code' => $order->order_code])">
Lihat Status Terbaru
</x-mail::button>

Jika informasi ini tidak sesuai, balas email ini agar tim kami bisa membantu.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
