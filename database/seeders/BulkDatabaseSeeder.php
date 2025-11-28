<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Package;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class BulkDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ensure packages exist
        $packages = Package::all();
        if ($packages->isEmpty()) {
            $this->call(PackageSeeder::class);
            $packages = Package::all();
        }

        $statusFlow = array_keys(config('orders.order_statuses'));

        // Create 50 Customers
        for ($i = 0; $i < 50; $i++) {
            $customer = Customer::create([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'email' => $faker->safeEmail,
            ]);

            // Create 1-5 orders per customer
            $orderCount = rand(1, 5);
            for ($j = 0; $j < $orderCount; $j++) {
                $package = $packages->random();
                $estimatedWeight = $package->billing_type === 'per_item' ? rand(1, 3) : rand(3, 12);

                // Randomize dates within last 3 months
                $createdAt = $faker->dateTimeBetween('-3 months', 'now');

                $status = Arr::random($statusFlow);
                $paymentMethod = Arr::random(['cash', 'qris', 'transfer']);
                $deliveryOption = Arr::random(['none', 'pickup', 'delivery']);
                $deliveryFee = $deliveryOption === 'delivery' ? 10000 : null;
                $totalPrice = $estimatedWeight * $package->price_per_kg;

                // Logic for actual weight and payment status
                $actualWeight = null;
                $paymentStatus = 'pending';

                if (in_array($status, ['processing', 'ready', 'taken', 'completed'])) {
                    $actualWeight = $estimatedWeight + ($faker->boolean(30) ? $faker->randomFloat(1, -0.5, 1.0) : 0);
                }

                if ($status === 'taken' || $status === 'completed') {
                    $paymentStatus = 'paid';
                } elseif ($paymentMethod === 'cash') {
                    $paymentStatus = 'pending';
                } else {
                    $paymentStatus = $faker->randomElement(['paid', 'pending']);
                }

                $order = Order::create([
                    'order_code' => Str::upper(Str::random(8)),
                    'customer_id' => $customer->id,
                    'package_id' => $package->id,
                    'estimated_weight' => $estimatedWeight,
                    'actual_weight' => $actualWeight,
                    'service_type' => $faker->randomElement(['regular', 'express', 'kilat']),
                    'notes' => $faker->boolean(20) ? $faker->sentence : null,
                    'status' => $status,
                    'price_per_kg' => $package->price_per_kg,
                    'total_price' => $totalPrice,
                    'payment_method' => $paymentMethod,
                    'payment_status' => $paymentStatus,
                    'queue_position' => rand(1, 50),
                    'estimated_completion' => $createdAt->modify('+' . ($package->turnaround_hours ?? 48) . ' hours'),
                    'pickup_or_delivery' => $deliveryOption,
                    'delivery_fee' => $deliveryFee,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // Create payment record if paid
                if ($paymentStatus === 'paid') {
                    $order->payments()->create([
                        'method' => $paymentMethod,
                        'amount' => $totalPrice + ($deliveryFee ?? 0),
                        'status' => 'paid',
                        'regeneration_count' => 0,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]);
                }
            }
        }
    }
}
