<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class QrisGenerator
{
    public function generate(float $amount, string $orderCode): array
    {
        $serverKey = config('midtrans.server_key');

        if (! $serverKey) {
            // In local/dev environments, allow a fallback QR so the UI keeps working.
            if (app()->environment('local', 'development', 'testing')) {
                return $this->fakeQrisPayload($amount, $orderCode);
            }

            throw new RuntimeException('MIDTRANS_SERVER_KEY belum dikonfigurasi.');
        }

        $orderId = 'QRIS-'.$orderCode.'-'.Str::upper(Str::random(4));
        $payload = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) round($amount),
            ],
            'qris' => [
                'acquirer' => config('midtrans.qris_acquirer', 'gopay'),
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.base64_encode($serverKey.':'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(config('midtrans.base_url').'/v2/charge', $payload)->throw()->json();

        $actions = collect($response['actions'] ?? []);
        $qrAction = $actions->firstWhere('name', 'generate-qr-code');
        $expiry = isset($response['expiry_time'])
            ? Carbon::parse($response['expiry_time'])
            : now()->addMinutes(config('orders.qris_expiry_minutes', 30));

        $qrString = $response['qr_string'] ?? $response['qr_str'] ?? null;
        $imageUrl = $qrAction['url'] ?? ($qrString ? 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data='.urlencode($qrString) : null);

        return [
            'transaction_id' => $response['transaction_id'] ?? $orderId,
            'payload' => $qrString,
            'qris_url' => $qrAction['url'] ?? ($response['deeplink_url'] ?? null),
            'qris_image_url' => $imageUrl,
            'expiry' => $expiry,
        ];
    }

    /**
     * Fallback QR generator for local/testing when Midtrans keys are absent.
     */
    protected function fakeQrisPayload(float $amount, string $orderCode): array
    {
        $qrContent = sprintf(
            'QRIS-DEV|order:%s|amount:%s|timestamp:%s',
            $orderCode,
            (int) round($amount),
            now()->timestamp
        );

        $imageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data='.urlencode($qrContent);

        return [
            'transaction_id' => 'DEV-'.$orderCode,
            'payload' => $qrContent,
            'qris_url' => $imageUrl,
            'qris_image_url' => $imageUrl,
            'expiry' => now()->addMinutes(config('orders.qris_expiry_minutes', 30)),
        ];
    }
}
