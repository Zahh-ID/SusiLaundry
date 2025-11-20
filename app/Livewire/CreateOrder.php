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
    public $name;
    public $email;
    public $address;
    public $package_id;
    public $estimated_weight;
    public $service_type = 'regular';
    public $notes;
    public $pickup_or_delivery = 'none';
    public $payment_method = 'cash';

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric|min:1',
            'service_type' => 'required|string',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'payment_method' => 'required|in:cash,qris',
            'notes' => 'nullable|string',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        $customerPayload = [
            'name' => $data['name'],
            'phone' => '',
            'address' => $data['address'],
            'email' => $data['email'],
        ];

        $customer = Customer::create($customerPayload);

        $package = Package::findOrFail($data['package_id']);
        $inactiveStatuses = config('orders.inactive_statuses', ['completed', 'cancelled']);
        $queuePosition = Order::whereNotIn('status', $inactiveStatuses)->count() + 1;
        $estimatedCompletion = now()->addHours($package->turnaround_hours ?? 48);

        $order = Order::create([
            'order_code' => Str::upper(Str::random(10)),
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'estimated_weight' => $data['estimated_weight'],
            'service_type' => $data['service_type'],
            'notes' => $data['notes'] ?? null,
            'status' => Order::initialStatus(),
            'price_per_kg' => $package->price_per_kg,
            'payment_method' => $data['payment_method'],
            'payment_status' => 'pending',
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $data['pickup_or_delivery'],
            'delivery_fee' => $this->resolveDeliveryFee($data['pickup_or_delivery']),
            'activity_log' => [],
        ]);

        $order->appendActivity('guest', 'order_created', [
            'pickup_or_delivery' => $data['pickup_or_delivery'],
            'contact_email' => $data['email'],
        ]);

        app(OrderEmailNotifier::class)->sendOrderCreated($order->fresh('customer', 'package'), $data['email']);

        $this->resetForm();

        return redirect()->route('order.success', ['code' => $order->order_code]);
    }

    protected function resolveDeliveryFee(string $pickupOption): ?float
    {
        return $pickupOption === 'delivery' ? 10000 : null;
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'email',
            'address',
            'package_id',
            'estimated_weight',
            'service_type',
            'notes',
            'pickup_or_delivery',
            'payment_method',
        ]);

        $this->service_type = 'regular';
        $this->pickup_or_delivery = 'none';
        $this->payment_method = 'cash';
        $this->email = null;
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
