<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class WhatsappNotifier
{
    public function enabled(): bool
    {
        return (bool) config('whatsapp.enabled')
            && config('whatsapp.access_token')
            && config('whatsapp.from_phone_id');
    }

    public function notifyOrderCreated(Order $order): void
    {
        if (! $this->enabled()) {
            return;
        }

        $message = sprintf(
            "Halo %s! Pesanan laundry kamu (%s) telah kami terima.\nStatus: %s\nEstimasi selesai: %s",
            $order->customer?->name ?? 'Pelanggan',
            $order->order_code,
            $order->status_label,
            optional($order->estimated_completion)->translatedFormat('d M Y H:i') ?? 'menunggu konfirmasi'
        );

        $this->sendText($order->customer?->phone, $message);
    }

    public function notifyStatusUpdated(Order $order): void
    {
        if (! $this->enabled()) {
            return;
        }

        $message = sprintf(
            "Update terbaru untuk %s:\nStatus: %s\nPembayaran: %s\nTotal: Rp %s",
            $order->order_code,
            $order->status_label,
            $order->payment_status_label,
            number_format($order->total_price ?? 0, 0, ',', '.')
        );

        $this->sendText($order->customer?->phone, $message);
    }

    public function notifyPaymentRequest(Order $order, Payment $payment, string $trackingUrl): void
    {
        if (! $this->enabled()) {
            return;
        }

        $message = sprintf(
            "Pesanan %s siap diproses dengan berat %.1f kg. Total Rp %s. Buka tautan berikut untuk melihat progres dan bayar via QRIS: %s\nQR berlaku hingga %s.",
            $order->order_code,
            $order->actual_weight ?? $order->estimated_weight ?? 0,
            number_format($payment->amount ?? 0, 0, ',', '.'),
            $trackingUrl,
            optional($payment->expiry_time)->translatedFormat('d M Y H:i') ?? '-'
        );

        $this->sendText($order->customer?->phone, $message);
    }

    public function notifyPaymentSuccess(Order $order): void
    {
        if (! $this->enabled()) {
            return;
        }

        $message = sprintf(
            "Pembayaran untuk pesanan %s berhasil. Kami lanjutkan proses laundry dan akan mengabari saat siap diambil.",
            $order->order_code
        );

        $this->sendText($order->customer?->phone, $message);
    }

    protected function sendText(?string $phone, string $body): void
    {
        if (! $phone) {
            return;
        }

        $phone = $this->sanitizePhone($phone);

        try {
            Http::withToken(config('whatsapp.access_token'))
                ->post(
                    sprintf(
                        'https://graph.facebook.com/%s/%s/messages',
                        config('whatsapp.api_version'),
                        config('whatsapp.from_phone_id')
                    ),
                    [
                        'messaging_product' => 'whatsapp',
                        'to' => $phone,
                        'type' => 'text',
                        'text' => [
                            'body' => Str::limit($body, 1024),
                            'preview_url' => false,
                        ],
                    ]
                )->throw();
        } catch (\Throwable $e) {
            report(new RuntimeException('WhatsApp notification failed: '.$e->getMessage(), 0, $e));
        }
    }

    protected function sanitizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (Str::startsWith($digits, '0')) {
            return config('whatsapp.fallback_country_code').substr($digits, 1);
        }

        return $digits;
    }
}
