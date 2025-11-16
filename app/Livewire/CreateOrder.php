<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use App\Services\QrisGenerator;
use App\Services\WhatsappNotifier;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateOrder extends Component
{
    public $name;
    public $phone;
    public $address;
    public $package_id;
    public $estimated_weight;
    public $service_type = 'regular';
    public $notes;
    public $payment_method = 'cash';
    public $pickup_or_delivery = 'none';

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric|min:1',
            'service_type' => 'required|string',
            'payment_method' => 'required|in:cash,qris',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'notes' => 'nullable|string',
        ]);

        $activeOrders = Order::whereHas('customer', function ($query) {
            $query->where('phone', $this->phone);
        })->whereNotIn('status', ['completed', 'cancelled'])->count();

        if ($activeOrders >= config('orders.max_active_orders_per_phone')) {
            $this->addError('phone', 'Nomor ini sudah memiliki pesanan aktif.');
            return;
        }

        $package = Package::findOrFail($this->package_id);
        $customer = Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        $queuePosition = Order::whereNotIn('status', ['completed', 'cancelled'])->count() + 1;
        $estimatedCompletion = now()->addHours($package->turnaround_hours ?? 48);
        $deliveryFee = $this->pickup_or_delivery === 'delivery' ? 10000 : null;
        $estimatedTotal = $package->price_per_kg * $this->estimated_weight;
        $amount = $estimatedTotal + ($deliveryFee ?? 0);

        $order = Order::create([
            'order_code' => Str::upper(Str::random(10)),
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'estimated_weight' => $this->estimated_weight,
            'service_type' => $this->service_type,
            'notes' => $this->notes,
            'status' => 'order_created',
            'price_per_kg' => $package->price_per_kg,
            'total_price' => $estimatedTotal,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_method === 'qris' ? 'pending' : 'skipped',
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $this->pickup_or_delivery,
            'delivery_fee' => $deliveryFee,
            'activity_log' => [],
        ]);

        $order->appendActivity('guest', 'order_created', [
            'payment_method' => $this->payment_method,
            'pickup_or_delivery' => $this->pickup_or_delivery,
        ]);

        $paymentData = [
            'method' => $this->payment_method,
            'amount' => $amount,
            'status' => $this->payment_method === 'qris' ? 'pending' : 'skipped',
        ];

        if ($this->payment_method === 'qris') {
            try {
                $payload = app(QrisGenerator::class)->generate($amount, $order->order_code);
                $paymentData = array_merge($paymentData, [
                    'qris_url' => $payload['qris_url'],
                    'qris_image_url' => $payload['qris_image_url'],
                    'qris_payload' => $payload['payload'],
                    'midtrans_transaction_id' => $payload['transaction_id'],
                    'expiry_time' => $payload['expiry'],
                ]);
                $order->appendActivity('system', 'qris_generated', [
                    'reference' => $payload['transaction_id'],
                    'expires_at' => $payload['expiry']->toIso8601String(),
                ]);
            } catch (\Throwable $th) {
                report($th);
                $order->update([
                    'payment_method' => 'cash',
                    'payment_status' => 'skipped',
                ]);
                $paymentData = [
                    'method' => 'cash',
                    'amount' => $amount,
                    'status' => 'skipped',
                ];
                session()->flash('error', 'QRIS sementara tidak tersedia. Pembayaran akan dilakukan secara tunai.');
            }
        }

        $order->payments()->create($paymentData);

        app(WhatsappNotifier::class)->notifyOrderCreated($order);

        return redirect()->route('order.success', ['code' => $order->order_code]);
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'phone',
            'address',
            'package_id',
            'estimated_weight',
            'service_type',
            'notes',
            'payment_method',
            'pickup_or_delivery',
        ]);
        $this->service_type = 'regular';
        $this->payment_method = 'cash';
        $this->pickup_or_delivery = 'none';
    }

    public function render()
    {
        $packages = Package::all();

        return view('livewire.create-order', [
            'packages' => $packages,
            'paymentMethods' => config('orders.payment_methods'),
            'pickupOptions' => config('orders.pickup_options'),
        ])->layout('layouts.site', ['title' => 'Form Pemesanan Laundry']);
    }
}
