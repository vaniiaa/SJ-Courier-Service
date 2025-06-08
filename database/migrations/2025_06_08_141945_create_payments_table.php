<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id('paymentID');
            $table->foreignId('orderID')->constrained('orders', 'orderID')->onDelete('cascade');
            $table->string('midtrans_transaction_id')->unique()->nullable();
            $table->unsignedBigInteger('amount');
            $table->string('paymentMethod')->nullable(); // Mis: 'midtrans_gopay', 'midtrans_bca_va'
            $table->timestamp('paymentDate')->nullable();
            $table->string('status')->default('Pending'); // Pending, Success, Failed, Expired
            $table->text('raw_midtrans_response')->nullable(); // Untuk menyimpan response lengkap dari Midtrans
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
