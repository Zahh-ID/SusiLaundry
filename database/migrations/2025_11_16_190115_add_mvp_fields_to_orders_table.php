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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('price_per_kg', 10, 2)->nullable()->after('estimated_weight');
            $table->string('payment_method')->default('cash')->after('total_price');
            $table->string('payment_status')->default('pending')->after('payment_method');
            $table->integer('queue_position')->nullable()->after('payment_status');
            $table->timestamp('estimated_completion')->nullable()->after('queue_position');
            $table->string('pickup_or_delivery')->default('none')->after('estimated_completion');
            $table->decimal('delivery_fee', 10, 2)->nullable()->after('pickup_or_delivery');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete()->after('delivery_fee');
            $table->json('activity_log')->nullable()->after('admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('admin_id');
            $table->dropColumn([
                'price_per_kg',
                'payment_method',
                'payment_status',
                'queue_position',
                'estimated_completion',
                'pickup_or_delivery',
                'delivery_fee',
                'activity_log',
            ]);
        });
    }
};
