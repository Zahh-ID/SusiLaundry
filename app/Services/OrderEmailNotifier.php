<?php

namespace App\Services;

use App\Mail\OrderCreatedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderEmailNotifier
{
    public function sendOrderCreated(Order $order, ?string $emailOverride = null): void
    {
        $order->loadMissing('customer', 'package');
        $email = $emailOverride ?? $this->resolveEmail($order);
        if (! $email) {
            return;
        }

        Mail::to($email)->send(new OrderCreatedMail($order));
    }

    public function sendStatusUpdated(Order $order, ?string $additionalMessage = null, ?string $emailOverride = null): void
    {
        $order->loadMissing('customer', 'package');
        $email = $emailOverride ?? $this->resolveEmail($order);

        if (! $email) {
            return;
        }

        Mail::to($email)->send(new OrderStatusUpdatedMail($order, $additionalMessage));
    }

    protected function resolveEmail(Order $order): ?string
    {
        if ($order->customer?->email) {
            return $order->customer->email;
        }

        $log = collect($order->activity_log ?? []);
        return $log->pluck('meta.contact_email')->filter()->last();
    }
}
