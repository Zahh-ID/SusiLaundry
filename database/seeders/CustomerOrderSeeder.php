<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CustomerOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Ayu Lestari',
                'phone' => '0812-1111-2222',
                'address' => 'Perum Cendana Blok B2 No.5, Sleman',
            ],
            [
                'name' => 'Rian Prasetyo',
                'phone' => '0813-9876-5432',
                'address' => 'Jl. Kaliurang KM 9, Gang Kenanga No.8',
            ],
            [
                'name' => 'Dewi Rahmania',
                'phone' => '0821-5555-8888',
                'address' => 'Apartemen Malioboro Park Tower B-12',
            ],
            [
                'name' => 'Michael Santoso',
                'phone' => '0817-2345-6789',
                'address' => 'Ruko Mataram Square No.3',
            ],
        ];

        $packages = Package::all();
        if ($packages->isEmpty()) {
            $this->call(PackageSeeder::class);
            $packages = Package::all();
        }

        $statusFlow = array_keys(config('orders.order_statuses'));

        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);

            $orderCount = rand(1, 3);
            for ($i = 0; $i < $orderCount; $i++) {
                $package = $packages->random();
                $estimatedWeight = $package->billing_type === 'per_item' ? rand(1, 3) : rand(3, 8);
                $status = Arr::random($statusFlow);
                $paymentMethod = Arr::random(['cash', 'qris']);
                $deliveryOption = Arr::random(['none', 'pickup', 'delivery']);
                $deliveryFee = $deliveryOption === 'delivery' ? 10000 : null;
                $totalPrice = $estimatedWeight * $package->price_per_kg;

                $order = Order::create([
                    'order_code' => Str::upper(Str::random(8)),
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'estimated_weight' => $estimatedWeight,
                    'actual_weight' => $status === 'taken' ? $estimatedWeight + rand(-1, 1) : null,
                    'service_type' => $i % 3 === 0 ? 'express' : 'regular',
                    'notes' => $i === 0 ? 'Pisahkan pakaian putih.' : null,
                    'status' => $status,
                    'price_per_kg' => $package->price_per_kg,
                    'total_price' => $totalPrice,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $status === 'taken' ? 'paid' : ($paymentMethod === 'qris' ? 'pending' : 'skipped'),
                    'queue_position' => rand(1, 25),
                    'estimated_completion' => now()->addHours($package->turnaround_hours ?? 48),
                    'pickup_or_delivery' => $deliveryOption,
                    'delivery_fee' => $deliveryFee,
                    'activity_log' => [],
                ]);

                $order->payments()->create([
                    'method' => $paymentMethod,
                    'amount' => $totalPrice + ($deliveryFee ?? 0),
                    'status' => $order->payment_status,
                    'regeneration_count' => 0,
                ]);
            }
        }
    }
}
