<?php

namespace App\Livewire\Admin\Order;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
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
    public $step = 1;

    // Step 1: Layanan
    public $package_id;
    public $actual_weight; // Changed from estimated_weight

    // Step 2: Detail
    public $name;
    public $email;
    public $address;
    public $pickup_or_delivery = 'none';
    public $notes;

    // Step 3: Review & Payment
    public $payment_method = 'cash';
    public $delivery_fee;
    public bool $markAsPaid = false; // Add this property

    public $successCode;
    public bool $showQrisModal = false;
    public array $pendingOrderPayload = [];
    public array $pendingQrisPayload = [];
    public ?int $pendingPaymentId = null;
    public bool $embedded = false;

    public function mount(): void
    {
        // No special mount logic needed for now
    }

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
                'actual_weight' => [
                    'required',
                    'numeric',
                    'min:0.1',
                    'max:999999.99',
                    'regex:/^\d+(\.\d{1,2})?$/'
                ],
            ]);
        } elseif ($step === 2) {
            $this->validate([
                'name' => 'required|string|min:3|max:255',
                'email' => 'nullable|email|max:255', // Email optional for offline
                'address' => 'required|string|min:5|max:500',
                'pickup_or_delivery' => 'required|in:none,pickup,delivery',
                'notes' => 'nullable|string|max:1000',
            ]);
        }
    }

    public function save(): void
    {
        $this->validate([
            'payment_method' => 'required|in:cash,qris',
        ]);

        $data = [
            'package_id' => $this->package_id,
            'actual_weight' => $this->actual_weight,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'pickup_or_delivery' => $this->pickup_or_delivery,
            'notes' => $this->notes,
            'payment_method' => $this->payment_method,
            'status' => Order::initialStatus(),
        ];

        if ($this->payment_method === 'qris') {
            $this->createOrderWithQris($data);
            return;
        }

        // Handle Cash Payment status override
        $paymentOverrides = [];
        if ($this->payment_method === 'cash' && $this->markAsPaid) {
            $paymentOverrides = [
                'payment_status' => 'paid',
                'status' => 'paid', // Payment model status
            ];
        }

        $order = $this->createOrder($data, $paymentOverrides);

        // Prevent Resend API rate limit (2 req/sec) if sending multiple emails
        if (!empty($data['email']) && ($paymentOverrides['payment_status'] ?? '') === 'paid') {
            sleep(1);
        }

        $this->afterOrderCreated($order);
    }

    protected function createOrderWithQris(array $data): void
    {
        $package = Package::findOrFail($data['package_id']);
        $pricePerKg = $package->price_per_kg;
        $deliveryFee = $this->resolveDeliveryFee($data['pickup_or_delivery']);
        $totalPrice = ($pricePerKg * $data['actual_weight']) + ($deliveryFee ?? 0);
        $orderCode = Str::upper(Str::random(10));

        try {
            $payload = app(QrisGenerator::class)->generate($totalPrice, $orderCode);
        } catch (\Throwable $th) {
            report($th);
            session()->flash('error', 'QRIS gagal dibuat. Coba beberapa saat lagi.');
            return;
        }

        $expiry = $payload['expiry'] ?? now()->addMinutes(config('orders.qris_expiry_minutes', 10));

        $order = $this->createOrder(
            array_merge($data, [
                'order_code' => $orderCode,
                'price_per_kg' => $pricePerKg,
                'delivery_fee' => $deliveryFee,
                'total_price' => $totalPrice,
            ]),
            [
                'payment_status' => 'pending',
                'status' => 'pending',
                'qris_url' => $payload['qris_url'],
                'qris_image_url' => $payload['qris_image_url'],
                'qris_payload' => $payload['payload'],
                'midtrans_transaction_id' => $payload['transaction_id'],
                'expiry_time' => $expiry,
            ]
        );

        $payment = $order->payments->first();
        $this->pendingPaymentId = $payment?->id;
        $this->pendingQrisPayload = [
            'transaction_id' => $payload['transaction_id'],
            'qris_url' => $payload['qris_url'],
            'qris_image_url' => $payload['qris_image_url'],
            'payload' => $payload['payload'],
            'expiry' => $expiry instanceof Carbon ? $expiry->toIso8601String() : (string) $expiry,
            'amount' => $totalPrice,
            'order_id' => $order->id,
            'payment_id' => $payment?->id,
        ];

        $this->showQrisModal = true;
        $this->afterOrderCreated($order);
    }

    public function checkPendingPaymentStatus(): void
    {
        if (!$this->showQrisModal || empty($this->pendingQrisPayload['transaction_id'])) {
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
        $payment = $this->pendingPaymentId ? Payment::find($this->pendingPaymentId) : null;
        $order = $payment?->order ?? (isset($this->pendingQrisPayload['order_id']) ? Order::find($this->pendingQrisPayload['order_id']) : null);

        if (in_array($transactionStatus, ['settlement', 'capture'], true)) {
            if ($payment) {
                $payment->update(['status' => 'paid']);
            }

            if ($order) {
                $order->update(['payment_status' => 'paid']);
                $order->appendActivity('system', 'payment_settled', [
                    'method' => 'qris',
                    'transaction_id' => $this->pendingQrisPayload['transaction_id'] ?? null,
                ]);
                app(PaymentReceiptMailer::class)->send($order->fresh('customer', 'package'));
            }

            session()->flash('message', 'Pembayaran QRIS terkonfirmasi dan pesanan sudah tercatat.');
            $this->resetPendingQris();
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            if ($payment) {
                $payment->update(['status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            }
            if ($order) {
                $order->update(['payment_status' => $transactionStatus === 'expire' ? 'expired' : 'failed']);
            }
            $this->cancelPendingQris('Pembayaran dibatalkan atau kedaluwarsa.');
        }
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
        $this->pendingPaymentId = null;
    }

    protected function createOrder(array $data, array $paymentOverrides = []): Order
    {
        $package = Package::findOrFail($data['package_id']);
        $pricePerKg = $data['price_per_kg'] ?? $package->price_per_kg;
        $deliveryFee = $this->resolveDeliveryFee($data['pickup_or_delivery']);
        $totalPrice = $data['total_price'] ?? (($pricePerKg * $data['actual_weight']) + ($deliveryFee ?? 0));

        $inactiveStatuses = config('orders.inactive_statuses', ['completed', 'cancelled']);
        $queuePosition = Order::whereNotIn('status', $inactiveStatuses)->count() + 1;
        $estimatedCompletion = now()->addHours($package->turnaround_hours ?? 48);

        // Determine service type
        $serviceType = 'regular';
        if (Str::contains(Str::lower($package->package_name), 'express')) {
            $serviceType = 'express';
        } elseif (Str::contains(Str::lower($package->package_name), 'kilat')) {
            $serviceType = 'kilat';
        }

        $customerPayload = [
            'name' => $data['name'],
            'phone' => '',
            'address' => $data['address'],
        ];

        if (!empty($data['email']) && Schema::hasColumn('customers', 'email')) {
            $customerPayload['email'] = $data['email'];
        }

        $customer = Customer::create($customerPayload);

        $order = Order::create([
            'order_code' => $data['order_code'] ?? Str::upper(Str::random(10)),
            'customer_id' => $customer->id,
            'package_id' => $data['package_id'],
            'estimated_weight' => $data['actual_weight'], // Use actual as estimated initially
            'actual_weight' => $data['actual_weight'],   // Set actual weight directly
            'service_type' => $serviceType,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'] ?? Order::initialStatus(),
            'price_per_kg' => $pricePerKg,
            'total_price' => $totalPrice,
            'payment_method' => $data['payment_method'],
            'payment_status' => $paymentOverrides['payment_status'] ?? ($data['payment_method'] === 'qris' ? 'pending' : 'skipped'),
            'queue_position' => $queuePosition,
            'estimated_completion' => $estimatedCompletion,
            'pickup_or_delivery' => $data['pickup_or_delivery'],
            'delivery_fee' => $deliveryFee,
            'activity_log' => [],
        ]);

        $paymentData = array_merge([
            'method' => $data['payment_method'],
            'amount' => $totalPrice,
            'status' => $paymentOverrides['status'] ?? ($data['payment_method'] === 'qris' ? 'pending' : 'skipped'),
        ], $paymentOverrides);

        $payment = $order->payments()->create($paymentData);
        $order->setRelation('payments', collect([$payment]));

        $order->appendActivity('admin', 'order_created', [
            'payment_method' => $data['payment_method'],
            'payment_status' => $order->payment_status,
            'actual_weight' => $data['actual_weight'],
        ]);

        if (!empty($data['email'])) {
            app(OrderEmailNotifier::class)->sendOrderCreated($order->fresh('customer', 'package'), $data['email']);
        }

        return $order;
    }

    protected function resolveDeliveryFee(string $pickupOption): ?float
    {
        return $pickupOption === 'delivery' ? 10000 : null;
    }

    protected function afterOrderCreated(Order $order): void
    {
        if ($order->payment_status === 'paid') {
            app(PaymentReceiptMailer::class)->send($order->fresh('customer', 'package'));
        }

        $this->successCode = $order->order_code;
        $message = 'Pesanan berhasil dibuat.';
        session()->flash('message', $message);
        $this->resetFormFields();
        $this->dispatch('order-created', id: $order->id, message: $message);
    }

    protected function resetFormFields(): void
    {
        $this->reset([
            'step',
            'name',
            'email',
            'address',
            'package_id',
            'actual_weight',
            'notes',
            'payment_method',
            'pickup_or_delivery',
            'delivery_fee'
        ]);
        $this->step = 1;
        $this->payment_method = 'cash';
        $this->pickup_or_delivery = 'none';
    }

    public function getTotalPriceProperty()
    {
        if (!$this->package_id || !$this->actual_weight)
            return 0;

        $package = Package::find($this->package_id);
        if (!$package)
            return 0;

        $basePrice = $package->price_per_kg * $this->actual_weight;
        $deliveryFee = $this->resolveDeliveryFee($this->pickup_or_delivery) ?? 0;

        return $basePrice + $deliveryFee;
    }

    public function render()
    {
        $view = view('livewire.admin.order.create', [
            'packages' => Package::orderBy('package_name')->get(),
            'paymentMethods' => config('orders.payment_methods'),
            'pickupOptions' => config('orders.pickup_options'),
            'embedded' => $this->embedded,
        ]);

        return $this->embedded
            ? $view
            : $view->layout('layouts.admin', ['title' => 'Input Pesanan Offline']);
    }
}
