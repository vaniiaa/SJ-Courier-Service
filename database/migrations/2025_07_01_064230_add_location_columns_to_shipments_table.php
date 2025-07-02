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
        Schema::table('shipments', function (Blueprint $table) {
            // Kolom untuk menyimpan lokasi kurir saat ini.
            // Menggunakan decimal untuk presisi yang lebih baik daripada float.
            $table->decimal('current_lat', 10, 7)->nullable()->after('weightKG');
            $table->decimal('current_long', 11, 7)->nullable()->after('current_lat');
            $table->timestamp('last_tracked_at')->nullable()->after('current_long');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['current_lat', 'current_long', 'last_tracked_at']);
        });
    }
};