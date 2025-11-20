<?php

namespace App\Livewire\Admin\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use App\Services\MidtransClient;
use App\Services\OrderEmailNotifier;
use App\Services\PaymentReceiptMailer;
use App\Services\QrisGenerator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    public $name;
    public $email;
    public $address;
    public $package_id;
    public $estimated_weight;
    public $service_type = 'regular';
    public $notes;
    public $payment_method = 'cash';
    public $pickup_or_delivery = 'none';
    public $delivery_fee;
    public $successCode;
    public array $paymentMethods = [];
    public array $pickupOptions = [];
    public $initialStatusLabel;
    public bool $showQrisModal = false;
    public array $pendingOrderPayload = [];
    public array $pendingQrisPayload = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'estimated_weight' => 'required|numeric|min:1',
            'service_type' => 'required|string',
            'payment_method' => 'required|in:cash,qris',
            'pickup_or_delivery' => 'required|in:none,pickup,delivery',
            'delivery_fee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(): void
    {
        $this->paymentMethods = config('orders.payment_methods');
        $this->pickupOptions = config('orders.pickup_options');
        $statuses = config('orders.order_statuses', []);
        $initialKey = Order::initialStatus();
        $this->initialStatusLabel = $statuses[$initialKey] ?? 'Menunggu Konfirmasi';
    }

    public function save(): void
    {
        $data = $this->validate();
        $data['status'] = Order::initialStatus();

        if ($data['payment_method'] === 'qris') {
            $this->initiateQrisFlow($data);
            return;
        }

        $order = $this->createOrder($data);
        $this->afterOrderCreated($order);
    }

    protected function initiateQrisFlow(array $data): void
    {
        $package = Package::findOrFail($data['package_id']);
        $pricePerKg = $package->price_per_kg;
        $deliveryFee = $this->resolveDeliveryFee($data['pickup_or_delivery'], $data['delivery_fee'] ?? null);
        $estimatedTotal = $pricePerKg * $data['estimated_weight'];
        $amount = $estimatedTotal + ($deliveryFee ?? 0);
        $orderCode = Str::upper(Str::random(10));

        try {
            $payload = app(QrisGenerator::class)->generate($amount, $orderCode);
        } catch (\Throwable $th) {
            report($th);
            session()->flash('error', 'QRIS gagal dibuat. Coba beberapa saat lagi.');
            return;
        }

        $expiry = $payload['expiry'] ?? now()->addMinutes(config('orders.qris_expiry_minutes', 10));

        $this->pendingOrderPayload = array_merge($data, [
            'order_code' => $orderCode,
            'price_per_kg' => $pricePerKg,
            'delivery_fee' => $deliveryFee,
        ]);

        $this->pendingQrisPayload = [
            'transaction_id' => $payload['transaction_id'],
            'qris_url' => $payload['qris_url'],
            'qris_image_url' => $payload['qris_image_url'],
            'payload' => $payload['payload'],
            'expiry' => $expiry instanceof Carbon ? $expiry->toIso8601String() : (string) $expiry,
            'amount' => $amount,
        ];

        $this->showQrisModal = true;
    }

    public function checkPendingPaymentStatus(): void
    {
        if (! $this->showQrisModal || empty($this->pendingQrisPayload['transaction_id'])) {
            return;
        }

        $expiry = isset($this->pendingQrisPayload['expiry']) ? Carbon::parse($this->pendingQrisPayload['expiry']) : null;
        if ($expiry && now()->greaterThan($expiry)) {
            $this->cancelPendingQris('Pembayaran QRIS kedaluwarsa.');
            return;
        }

        try {
            $status = app(MidtransClient::class)->status($this->pendingQrisPayload['transaction_id']);
        } catch (\Throwable $th) {
            report($th);
            return;
        }

        $transactionStatus = $status['transaction_status'] ?? null;

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            $this->finalizePendingOrder();
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            $this->cancelPendingQris('Pembayaran dibatalkan atau kedaluwarsa.');
        }
    }

    protected function finalizePendingOrder(): void
    {
        if (empty($this->pendingOrderPayload)) {
            return;
        }

        $expiry = isset($this->pendingQrisPayload['expiry']) ? Carbon::parse($this->pendingQrisPayload['expiry']) : null;

        $order = $this->createOrder($this->pendingOrderPayload, [
            'status' => 'paid',
            'qris_url' => $this->pendingQrisPayload['qris_url'] ?? null,
            'qris_image_url' => $this->pendingQrisPayload['qris_image_url'] ?? null,
            'qris_payload' => $this->pendingQrisPayload['payload'] ?? null,
            'midtrans_transaction_id' => $this->pendingQrisPayload['transaction_id'] ?? null,
            'expiry_time' => $expiry,
        ]);

        $this->resetPendingQris();
        $this->afterOrderCreated($order);
    }

    public function cancelPendingQris(?string $message = null): void
    {
        $this->resetPendingQris();
        if ($message) {
            session()->flash('error', $message);
        }
    }

    protected function resetPendingQris(): void
    {
        $this->showQrisModal = false;
        $this->pendingOrderPayload = [];
        $this->pendingQrisPayload = [];
    }

    protected function createOrder(array $data, array $paymentOverrides = []): Order
    {
        $package = Package::findOrFail($data['package_id']);
        $pricePerKg = $data['price_per_kg'] ?? $package->price_per_kg;
        $deliveryFee = $this->resolveDeliveryFee($data['pickup_or_delivery'], $data['delivery_fee'] ?? null);
        $estimatedTotal = $pricePerKg * $data['estimated_weight'];
        $amount = $estimatedTotal + ($deliveryFee ?? 0);
        $inactiveStatuses = config('orders.inactive_statuses', ['completed', 'cancelled']);
        $queuePosition = Order::whereNotIn('status', $inactiveStatuses)->count() + 1;
        $estimatedCompletion = now()->addHours($package->turnaround_hours ?? 48);

        $customerPayload = [
            'name' => $data['name'],
            'phone' => '',
            'address' => $data['address'],
        ];

        if (Schema::hasColumn('customers', 'email')) {
            $customerPayload['email'] = $data['email'];
        }

        $customer = Customer::create($customerPayload);

        $order = Order::create([
            'order_code' => $data['order_code'] ?? Str::upper(Str::random(10)),
            'customer_id' => $customer->id,
            'package_id' => $data['package_id'],
            'estimated_weight' => $data['estimated_weight'],
            'service_type' => $data['service_type'],
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? Order::initialStatus(),
            'price_per_kg' => $pricePerKg,
            'total_price' => $estimatedTotal,
            'payment_method' => $data['payment_method'],
            'payment_status' => $data['payment_method'] === 'qris' ? 'paid' : 'skipped',
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $data['pickup_or_delivery'],
            'delivery_fee' => $deliveryFee,
            'activity_log' => [],
        ]);

        $paymentData = array_merge([
            'method' => $data['payment_method'],
            'amount' => $amount,
            'status' => $data['payment_method'] === 'qris' ? 'paid' : 'skipped',
        ], $paymentOverrides);

        $order->payments()->create($paymentData);

        $order->appendActivity('admin', 'order_created', [
            'payment_method' => $data['payment_method'],
            'payment_status' => $order->payment_status,
            'contact_email' => $data['email'] ?? null,
        ]);

        app(OrderEmailNotifier::class)->sendOrderCreated($order->fresh('customer', 'package'), $data['email'] ?? null);

        return $order;
    }

    protected function resolveDeliveryFee(string $pickupOption, ?float $customFee): ?float
    {
        if ($customFee !== null) {
            return $customFee;
        }

        return $pickupOption === 'delivery' ? 10000 : null;
    }

    protected function afterOrderCreated(Order $order): void
    {
        if ($order->payment_status === 'paid') {
            app(PaymentReceiptMailer::class)->send($order->fresh('customer', 'package'));
        }

        $this->successCode = $order->order_code;
        session()->flash('message', 'Pesanan berhasil dibuat.');
        $this->resetFormFields();
    }

    protected function resetFormFields(): void
    {
        $this->reset([
            'name',
            'email',
            'address',
            'package_id',
            'estimated_weight',
            'service_type',
            'notes',
            'payment_method',
            'pickup_or_delivery',
            'delivery_fee',
        ]);
        $this->service_type = 'regular';
        $this->payment_method = 'cash';
        $this->pickup_or_delivery = 'none';
        $this->delivery_fee = null;
        $this->email = null;
    }

    public function render()
    {
        return view('livewire.admin.order.create', [
            'packages' => Package::orderBy('package_name')->get(),
            'paymentMethods' => $this->paymentMethods,
            'pickupOptions' => $this->pickupOptions,
        ])->layout('layouts.admin', ['title' => 'Tambah Pesanan Manual']);
    }
}
