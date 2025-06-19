<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_histories', function (Blueprint $table) {
            $table->id('trackingHistoryID');
            $table->foreignId('shipmentID')->constrained('shipments', 'shipmentID')->onDelete('cascade');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('statusDescription');
            $table->decimal('locationLatitude', 10, 7)->nullable();
            $table->decimal('locationLongitude', 10, 7)->nullable();
            $table->foreignId('updatedByUserID')->nullable()->constrained('users', 'user_id')->onDelete('set null'); // Admin atau Kurir
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_histories');
    }
};

