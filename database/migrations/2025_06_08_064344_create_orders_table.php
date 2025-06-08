<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderID');
            $table->foreignId('senderUserID')->constrained('users', 'user_id')->onDelete('cascade'); 
            $table->string('receiverName');
            $table->text('receiverAddress'); // Alamat lengkap di Batam
            $table->string('receiverPhoneNumber');
            $table->foreignId('receiverUserID')->nullable()->constrained('users', 'user_id')->onDelete('set null');
            $table->text('pickupAddress'); // Alamat lengkap di Batam
            $table->date('orderDate');
            $table->text('notes')->nullable();
            $table->string('status')->default('Pending Payment'); // Contoh: Pending Payment, Pending Confirmation, Processing, etc.
            $table->decimal('estimatedDistanceKM', 10, 2)->nullable();
            $table->unsignedBigInteger('estimatedPrice')->nullable();
            $table->decimal('pickupLatitude', 10, 7)->nullable();
            $table->decimal('pickupLongitude', 10, 7)->nullable();
            $table->decimal('receiverLatitude', 10, 7)->nullable();
            $table->decimal('receiverLongitude', 10, 7)->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->string('midtrans_order_id')->unique()->nullable(); // Untuk menyimpan order_id Midtrans
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};