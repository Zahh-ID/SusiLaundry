<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Illuminate\Support\Str;
use Livewire\Component;

class WebhookSimulator extends Component
{
    public $order_code;
    public $status = 'settlement';
    public $response_message;
    public $response_success = false;

    public function simulate()
    {
        $this->validate([
            'order_code' => 'required|string|exists:orders,order_code',
            'status' => 'required|in:settlement,pending,expire,deny,cancel',
        ]);

        $order = Order::where('order_code', $this->order_code)->first();
        $payment = $order->payments()->where('method', 'qris')->latest()->first();

        if (!$payment) {
            $this->response_message = 'Order ini tidak memiliki pembayaran QRIS.';
            $this->response_success = false;
            return;
        }

        $serverKey = config('midtrans.server_key');
        $orderId = $payment->midtrans_transaction_id ?? 'QRIS-' . $order->order_code . '-SIMULATED';
        $grossAmount = (string) intval($payment->amount);
        $statusCode = '200';

        // Map status to transaction_status
        $transactionStatus = $this->status;
        if ($this->status === 'deny' || $this->status === 'cancel') {
            $statusCode = '202'; // Just an example, Midtrans codes vary
        }

        $signatureInput = $orderId . $statusCode . $grossAmount . $serverKey;

        // Midtrans signature logic: SHA512(order_id+status_code+gross_amount+ServerKey)
        // But we need to match what MidtransWebhookController expects.
        // It expects payload['gross_amount'] to be part of the hash.

        $payload = [
            'transaction_time' => now()->format('Y-m-d H:i:s'),
            'transaction_status' => $transactionStatus,
            'transaction_id' => $payment->midtrans_transaction_id ?? Str::uuid()->toString(),
            'status_message' => 'midtrans payment notification',
            'status_code' => $statusCode,
            'payment_type' => 'qris',
            'order_id' => $orderId,
            'merchant_id' => 'G123456789',
            'gross_amount' => $grossAmount . '.00',
            'fraud_status' => 'accept',
            'currency' => 'IDR',
        ];

        $signatureInput = $orderId . $statusCode . $payload['gross_amount'] . $serverKey;
        $payload['signature_key'] = hash('sha512', $signatureInput);

        $request = new \Illuminate\Http\Request();
        $request->replace($payload);

        $controller = new \App\Http\Controllers\Webhook\MidtransWebhookController();

        try {
            $response = $controller->__invoke($request);
            if ($response->getStatusCode() === 200) {
                $this->response_message = "Webhook berhasil dikirim! Status: $transactionStatus";
                $this->response_success = true;
            } else {
                $this->response_message = 'Webhook gagal: ' . $response->getContent();
                $this->response_success = false;
            }
        } catch (\Exception $e) {
            $this->response_message = 'Error: ' . $e->getMessage();
            $this->response_success = false;
        }
    }

    public function render()
    {
        return view('livewire.admin.webhook-simulator')
            ->layout('layouts.admin', ['title' => 'Secret Webhook Simulator']);
    }
}
