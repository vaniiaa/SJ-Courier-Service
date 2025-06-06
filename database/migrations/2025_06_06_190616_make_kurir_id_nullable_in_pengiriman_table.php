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
            // Drop foreign key yang lama jika ada (penting sebelum constrained ulang)
            // Ini asumsi nama foreign key standar Laravel. Anda mungkin perlu menyesuaikannya.
            $table->dropForeign(['kurir_id']); // Menghapus constraint yang lama terlebih dahulu

            // Mengubah kolom kurir_id menjadi nullable dan menunjuk ke tabel 'kurir' (singular)
            $table->foreignId('kurir_id')->nullable()->constrained('kurir')->onDelete('set null')->change();
                                                 // ^^^^^^ UBAH DI SINI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            // Drop foreign key yang baru
            $table->dropForeign(['kurir_id']); // Menghapus constraint yang baru

            // Mengubah kembali kolom kurir_id menjadi tidak nullable dan menunjuk ke tabel 'kurirs' (lama)
            // Hati-hati: ini akan membutuhkan data di kolom kurir_id tidak boleh NULL
            $table->foreignId('kurir_id')->constrained('kurirs')->onDelete('cascade')->change();
                                                 // ^^^^^^ SESUAIKAN JIKA DI REVERSE INGIN KE 'kurirs' LAGI
        });
    }
};