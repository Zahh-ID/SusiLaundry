<?php

namespace App\Observers;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
            // Send email to customer
            if ($order->customer && $order->customer->email) {
                Mail::to($order->customer->email)->send(new OrderStatusUpdatedMail($order));
            }
        }
    }
}
