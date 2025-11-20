<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentReceiptMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();

        if (! $this->hasValidSignature($payload)) {
            Log::warning('Midtrans webhook rejected: invalid signature', ['payload' => $payload]);

            return response()->json(['message' => 'Invalid signature'], Response::HTTP_FORBIDDEN);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        if (! $transactionStatus) {
            return response()->json(['message' => 'Missing transaction status'], Response::HTTP_BAD_REQUEST);
        }

        $paymentStatus = $this->mapPaymentStatus($transactionStatus, $payload['fraud_status'] ?? null);
        $payment = $this->findPaymentRecord($payload);

        if (! $payment) {
            Log::warning('Midtrans webhook ignored: payment not found', ['payload' => $payload]);

            return response()->json(['message' => 'Payment not found'], Response::HTTP_OK);
        }

        $previousPaymentStatus = $payment->status;

        $payment->fill([
            'status' => $paymentStatus,
            'meta' => array_merge($payment->meta ?? [], [
                'last_midtrans_notification' => [
                    'received_at' => now()->toIso8601String(),
                    'transaction_status' => $transactionStatus,
                    'fraud_status' => $payload['fraud_status'] ?? null,
                ],
            ]),
        ])->save();

        $order = $payment->order;
        $shouldSendReceipt = $paymentStatus === 'paid' && $previousPaymentStatus !== 'paid';

        if ($order) {
            $order->payment_status = $paymentStatus;
            $order->save();

            $order->appendActivity('system', 'payment_webhook', [
                'payment_status' => $paymentStatus,
                'transaction_status' => $transactionStatus,
            ]);

            if ($shouldSendReceipt) {
                app(PaymentReceiptMailer::class)->send($order, $payment->fresh());
            }
        }

        return response()->json(['success' => true]);
    }

    protected function hasValidSignature(array $payload): bool
    {
        $signature = $payload['signature_key'] ?? null;
        $expected = null;

        if (isset($payload['order_id'], $payload['status_code'], $payload['gross_amount'])) {
            $expected = hash('sha512', $payload['order_id'].$payload['status_code'].$payload['gross_amount'].config('midtrans.server_key'));
        }

        return $signature && $expected && hash_equals($expected, $signature);
    }

    protected function mapPaymentStatus(string $transactionStatus, ?string $fraudStatus): string
    {
        return match ($transactionStatus) {
            'capture' => $fraudStatus === 'challenge' ? 'pending' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending',
            'expire' => 'expired',
            'cancel', 'deny' => 'failed',
            default => 'pending',
        };
    }

    protected function findPaymentRecord(array $payload): ?Payment
    {
        $transactionId = $payload['transaction_id'] ?? $payload['order_id'] ?? null;

        if ($transactionId) {
            $payment = Payment::where('midtrans_transaction_id', $transactionId)->latest('id')->first();
            if ($payment) {
                return $payment;
            }
        }

        if (! empty($payload['order_id'])) {
            $orderCode = $this->extractOrderCode($payload['order_id']);
            if ($orderCode) {
                $order = Order::where('order_code', $orderCode)->first();
                if ($order) {
                    return $order->payments()->latest('id')->first();
                }
            }
        }

        return null;
    }

    protected function extractOrderCode(string $orderId): ?string
    {
        if (Str::startsWith($orderId, 'QRIS-')) {
            $withoutPrefix = Str::after($orderId, 'QRIS-');
            $parts = explode('-', $withoutPrefix);

            return $parts[0] ?? null;
        }

        return $orderId;
    }
}
