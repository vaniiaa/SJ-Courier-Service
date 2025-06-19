<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id('shipmentID');
            $table->foreignId('orderID')->unique()->constrained('orders', 'orderID')->onDelete('cascade');
            $table->foreignId('courierUserID')->nullable()->constrained('users', 'user_id')->onDelete('set null');
             // ... sisa kolom sama ...
            $table->string('itemType');
            $table->decimal('weightKG', 8, 2);
            $table->string('currentStatus')->default('Scheduled for Pickup');
            $table->timestamp('pickupTimestamp')->nullable();
            $table->timestamp('deliveredTimestamp')->nullable();
            $table->unsignedBigInteger('finalPrice');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('shipments'); }
};
