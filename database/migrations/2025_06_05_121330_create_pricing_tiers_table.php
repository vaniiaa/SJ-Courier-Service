<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_tiers', function (Blueprint $table) {
            $table->id('tierID');
            $table->string('description')->nullable();
            $table->decimal('minWeightKG', 8, 2);
            $table->decimal('maxWeightKG', 8, 2);
            $table->unsignedInteger('pricePerKM');
            $table->timestamps();
        });

        DB::table('pricing_tiers')->insert([
            ['description' => 'Paket Ringan (0.1 - 5 Kg)', 'minWeightKG' => 0.1, 'maxWeightKG' => 5.00, 'pricePerKM' => 4000, 'created_at' => now(), 'updated_at' => now()],
            ['description' => 'Paket Sedang (5.01 - 10 Kg)', 'minWeightKG' => 5.01, 'maxWeightKG' => 10.00, 'pricePerKM' => 5000, 'created_at' => now(), 'updated_at' => now()],
            // Tambahkan tier lain jika perlu
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_tiers');
    }
};
