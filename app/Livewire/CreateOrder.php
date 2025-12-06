<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use App\Services\OrderEmailNotifier;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateOrder extends Component
{
    public $step = 1;

    // Step 1: Layanan
    public $package_id;
    public $estimated_weight;

    // Step 2: Detail
    public $name;
    public $email;
    public $address;
    public $pickup_or_delivery = 'none';
    public $notes;

    // Step 3: Review & Payment
    public $payment_method = 'cash';

    public function nextStep()
    {
        $this->validateStep($this->step);
        $this->step++;
    }

    public function prevStep()
    {
        $this->step--;
    }

    public function setPackage($id)
    {
        $this->package_id = $id;
    }

    protected function validateStep($step)
    {
        if ($step === 1) {
            $this->validate([
                'package_id' => 'required|exists:packages,id',
                'estimated_weight' => 'required|numeric|min:1',
            ]);
        } elseif ($step === 2) {
            $this->validate([
                'name' => 'required|string|min:3',
                'email' => 'required|email',
                'address' => 'required|string|min:10',
                'pickup_or_delivery' => 'required|in:none,pickup,delivery',
                'notes' => 'nullable|string',
            ]);
        }
    }

    public function save()
    {
        $this->validate([
            'payment_method' => 'required|in:cash,qris',
        ]);

        $customerPayload = [
            'name' => $this->name,
            'phone' => '', // Optional for now
            'address' => $this->address,
            'email' => $this->email,
        ];

        $customer = Customer::create($customerPayload);

        $package = Package::findOrFail($this->package_id);
        $inactiveStatuses = config('orders.inactive_statuses', ['completed', 'cancelled']);
        $queuePosition = Order::whereNotIn('status', $inactiveStatuses)->count() + 1;
        $estimatedCompletion = now()->addHours($package->turnaround_hours ?? 48);

        // Determine service type from package name for DB consistency
        $serviceType = 'regular';
        if (Str::contains(Str::lower($package->package_name), 'express')) {
            $serviceType = 'express';
        } elseif (Str::contains(Str::lower($package->package_name), 'kilat')) {
            $serviceType = 'kilat';
        }

        $order = Order::create([
            'order_code' => Str::upper(Str::random(10)),
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'estimated_weight' => $this->estimated_weight,
            'service_type' => $serviceType,
            'notes' => $this->notes,
            'status' => Order::initialStatus(),
            'price_per_kg' => $package->price_per_kg,
            'payment_method' => $this->payment_method,
            'payment_status' => 'pending',
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $this->pickup_or_delivery,
            'delivery_fee' => $this->resolveDeliveryFee($this->pickup_or_delivery),
            'total_price' => $this->totalPrice,
            'activity_log' => [],
        ]);

        $order->appendActivity('guest', 'order_created', [
            'pickup_or_delivery' => $this->pickup_or_delivery,
            'contact_email' => $this->email,
        ]);

        // Create Payment record if QRIS
        if ($this->payment_method === 'qris') {
            $order->payments()->create([
                'method' => 'qris',
                'amount' => $order->total_price,
                'status' => 'pending',
                'qris_url' => 'https://example.com/pay/' . $order->order_code, // Dummy URL
                'qris_image_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . $order->order_code, // Generate QR based on order code
                'expiry_time' => now()->addHours(1),
                'regeneration_count' => 0,
            ]);
        }

        app(OrderEmailNotifier::class)->sendOrderCreated($order->fresh('customer', 'package'), $this->email);

        return redirect()->route('order.success', ['code' => $order->order_code]);
    }

    protected function resolveDeliveryFee(string $pickupOption): ?float
    {
        return $pickupOption === 'delivery' ? 10000 : null;
    }

    public function getTotalPriceProperty()
    {
        if (!$this->package_id || !$this->estimated_weight)
            return 0;

        $package = Package::find($this->package_id);
        if (!$package)
            return 0;

        $basePrice = $package->price_per_kg * $this->estimated_weight;
        $deliveryFee = $this->resolveDeliveryFee($this->pickup_or_delivery) ?? 0;

        return $basePrice + $deliveryFee;
    }

    public function render()
    {
        return view('livewire.create-order', [
            'packages' => Package::all(),
            'pickupOptions' => config('orders.pickup_options'),
            'paymentMethods' => config('orders.payment_methods'),
        ])->layout('layouts.site', ['title' => 'Form Pemesanan Laundry']);
    }
}
