<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('customer_id')->constrained('customers');
            $table->foreignId('package_id')->constrained('packages');
            $table->decimal('estimated_weight', 8, 2);
            $table->decimal('actual_weight', 8, 2)->nullable();
            $table->string('service_type');
            $table->text('notes')->nullable();
            $table->string('status');
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
