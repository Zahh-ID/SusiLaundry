<?php

namespace App\Services;

use App\Mail\PaymentReceiptMail;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class PaymentReceiptMailer
{
    public function send(Order $order, ?Payment $payment = null): void
    {
        $order->loadMissing('customer', 'package');

        $email = $order->customer?->email;

        if (! $email) {
            return;
        }

        $payment = $payment ?: $order->payments()->latest()->first();

        Mail::to($email)->send(new PaymentReceiptMail($order, $payment));
    }
}
