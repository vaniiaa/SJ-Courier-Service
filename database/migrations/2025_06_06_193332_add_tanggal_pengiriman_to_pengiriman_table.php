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
            // Tambahkan kolom 'tanggal_pengiriman' sebagai tipe date atau datetime
            // Saya sarankan 'date' jika Anda hanya menyimpan tanggal, atau 'datetime' jika ada waktu juga.
            // Sesuaikan dengan kebutuhan dan bagaimana Anda ingin menyimpan tanggal.
            $table->date('tanggal_pengiriman')->nullable()->after('nama_kurir'); // Contoh: setelah 'nama_kurir'

            // Jika Anda ingin menyimpan waktu juga, gunakan datetime:
            // $table->datetime('tanggal_pengiriman')->nullable()->after('nama_kurir');

            // Jika kolom ini WAJIB diisi, hapus ->nullable()
            // $table->date('tanggal_pengiriman')->after('nama_kurir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            // Ini penting untuk "mengembalikan" migrasi jika diperlukan
            $table->dropColumn('tanggal_pengiriman');
        });
    }
};