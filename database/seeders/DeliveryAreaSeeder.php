<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('delivery_area')->insert([
            ['area_name' => 'Sekupang'],
            ['area_name' => 'Batu Aji'],
            ['area_name' => 'Lubuk Baja'],
            ['area_name' => 'Nongsa'],
            ['area_name' => 'Galang'],
            ['area_name' => 'Belakang Padang'],
            ['area_name' => 'Bengkong'],
            ['area_name' => 'Sungai Beduk'],
            ['area_name' => 'Batam Kota'],
            ['area_name' => 'Batu Ampar'],
            ['area_name' => 'Bulang'],
            ['area_name' => 'Sagulung'],
        ]);
    }
}
