<?php

namespace App\Livewire\Admin\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use App\Services\QrisGenerator;
use App\Services\WhatsappNotifier;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $phone;
    public $address;
    public $package_id;
    public $estimated_weight;
    public $service_type = 'regular';
    public $notes;
    public $status = 'order_created';
    public $payment_method = 'cash';
    public $pickup_or_delivery = 'none';
    public $delivery_fee;
    public $successCode;
    public array $statuses = [];
    public array $paymentMethods = [];
    public array $pickupOptions = [];

    public function mount(): void
    {
        $this->statuses = config('orders.order_statuses');
        $this->paymentMethods = config('orders.payment_methods');
        $this->pickupOptions = config('orders.pickup_options');
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric|min:1',
            'service_type' => 'required|string',
            'status' => 'required|string',
            'payment_method' => 'required|in:cash,qris',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'notes' => 'nullable|string',
        ]);

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
            'package_id' => $this->package_id,
            'estimated_weight' => $this->estimated_weight,
            'service_type' => $this->service_type,
            'notes' => $this->notes,
            'status' => $this->status ?: 'order_created',
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
                session()->flash('error', 'Tidak dapat menghasilkan QRIS, pembayaran dialihkan ke tunai.');
            }
        }

        $order->payments()->create($paymentData);

        app(WhatsappNotifier::class)->notifyOrderCreated($order);

        $this->successCode = $order->order_code;
        session()->flash('message', 'Pesanan berhasil dibuat.');

        $this->reset([
            'name',
            'phone',
            'address',
            'package_id',
            'estimated_weight',
            'service_type',
            'notes',
            'status',
            'payment_method',
            'pickup_or_delivery',
            'delivery_fee',
        ]);

        $this->service_type = 'regular';
        $this->status = 'order_created';
        $this->payment_method = 'cash';
        $this->pickup_or_delivery = 'none';
    }

    public function render()
    {
        $this->status = $this->status ?? 'order_created';

        return view('livewire.admin.order.create', [
            'packages' => Package::orderBy('package_name')->get(),
            'statuses' => $this->statuses,
            'paymentMethods' => $this->paymentMethods,
            'pickupOptions' => $this->pickupOptions,
        ])->layout('layouts.admin', ['title' => 'Tambah Pesanan Manual']);
    }
}
