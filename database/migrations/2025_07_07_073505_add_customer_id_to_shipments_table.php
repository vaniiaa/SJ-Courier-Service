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
            // Menambahkan kolom kecamatan untuk pengirim dan penerima
            $table->string('pickupKecamatan', 100)->nullable()->after('pickupAddress');
            $table->string('receiverKecamatan', 100)->nullable()->after('receiverAddress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom kecamatan
            $table->dropColumn(['pickupKecamatan', 'receiverKecamatan']);
        });
    }
};