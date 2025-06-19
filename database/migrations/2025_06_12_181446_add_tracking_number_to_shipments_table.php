<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('shipments', function (Blueprint $table) {
            // Menambahkan kolom setelah 'shipmentID'
            $table->string('tracking_number')->unique()->after('shipmentID');
        });
    }
    public function down(): void {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn('tracking_number');
        });
    }
};