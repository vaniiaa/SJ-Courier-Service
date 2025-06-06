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
        Schema::table('pengiriman', function (Blueprint $table) {
            // Tambahkan kolom 'catatan' sebagai tipe string (VARCHAR)
            // Saya sarankan membuatnya nullable karena di controller Anda juga mengizinkannya nullable.
            $table->string('catatan', 500)->nullable()->after('tanggal_pengiriman'); // Contoh: setelah 'tanggal_pengiriman'

            // Jika Anda merasa catatan bisa sangat panjang, bisa pakai 'text'
            // $table->text('catatan')->nullable()->after('tanggal_pengiriman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            // Ini penting untuk "mengembalikan" migrasi jika diperlukan
            $table->dropColumn('catatan');
        });
    }
};