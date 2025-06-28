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
        // Tambahkan kolom catatan ke shipments
        Schema::table('shipments', function (Blueprint $table) {
        $table->string('noteadmin', 255)->nullable()->after('currentStatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            // Hapus kolom catatan jika migrasi dibatalkan
            $table->dropColumn('noteadmin');
        });
    }
};
