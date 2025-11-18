<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
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
    public $pickup_or_delivery = 'none';

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric|min:1',
            'service_type' => 'required|string',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'notes' => 'nullable|string',
        ];
    }

    public function save()
    {
        $data = $this->validate();

        $customer = Customer::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
        ]);

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
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $data['pickup_or_delivery'],
            'delivery_fee' => $this->resolveDeliveryFee($data['pickup_or_delivery']),
            'activity_log' => [],
        ]);

        $order->appendActivity('guest', 'order_created', [
            'pickup_or_delivery' => $data['pickup_or_delivery'],
        ]);

        app(WhatsappNotifier::class)->notifyOrderCreated($order);

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
            'phone',
            'address',
            'package_id',
            'estimated_weight',
            'service_type',
            'notes',
            'pickup_or_delivery',
        ]);

        $this->service_type = 'regular';
        $this->pickup_or_delivery = 'none';
    }

    public function render()
    {
        return view('livewire.create-order', [
            'packages' => Package::all(),
            'pickupOptions' => config('orders.pickup_options'),
        ])->layout('layouts.site', ['title' => 'Form Pemesanan Laundry']);
    }
}
