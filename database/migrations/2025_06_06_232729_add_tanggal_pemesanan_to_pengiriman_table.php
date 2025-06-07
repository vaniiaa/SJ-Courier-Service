<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_tanggal_pemesanan_to_pengiriman_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->date('tanggal_pemesanan')->nullable()->after('some_existing_column'); // Sesuaikan 'some_existing_column'
            // Jika Anda perlu mengganti nama kolom yang salah, gunakan:
            // $table->renameColumn('nama_kolom_lama', 'tanggal_pemesanan');
        });
    }

    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->dropColumn('tanggal_pemesanan');
        });
    }
};