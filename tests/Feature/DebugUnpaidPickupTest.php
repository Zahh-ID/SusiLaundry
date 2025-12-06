<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Package;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DebugUnpaidPickupTest extends TestCase
{
    use RefreshDatabase;

    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_it_dumps_values_when_updating_unpaid_order_to_taken()
    {
        // Create admin
        $admin = User::factory()->create(['role' => 'admin']);

        // Create dependencies
        $customer = Customer::create([
            'name' => 'Test Customer',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'email' => 'test@example.com',
        ]);

        $package = Package::create([
            'package_name' => 'Test Package',
            'description' => 'Desc',
            'price_per_kg' => 5000,
            'billing_type' => 'per_kg',
            'turnaround_hours' => 24,
        ]);

        // Create order
        $order = Order::create([
            'order_code' => 'TESTCODE',
            'customer_id' => $customer->id,
            'package_id' => $package->id,
            'estimated_weight' => 10,
            'service_type' => 'regular',
            'status' => 'processing',
            'price_per_kg' => 5000,
            'payment_method' => 'cash',
            'payment_status' => 'pending', // Unpaid
            'pickup_or_delivery' => 'none',
        ]);

        // Act as admin
        $this->actingAs($admin);

        // Attempt to update status to 'taken' via Livewire
        // Livewire::test(\App\Livewire\Admin\Order\Edit::class, ['order' => $order])
        //     ->set('status', 'taken') // Try to set to taken
        //     ->set('payment_status', 'pending') // Ensure it stays unpaid
        //     ->call('update');
    }
}
