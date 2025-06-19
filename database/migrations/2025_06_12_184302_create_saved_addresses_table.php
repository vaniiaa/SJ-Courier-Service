<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('saved_addresses', function (Blueprint $table) {
            $table->id();
            // Merujuk ke Primary Key 'user_id' Anda
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->string('label'); // Contoh: "Rumah", "Kantor"
            $table->text('address');
            $table->string('latitude', 15)->nullable();
            $table->string('longitude', 15)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('saved_addresses'); }
};