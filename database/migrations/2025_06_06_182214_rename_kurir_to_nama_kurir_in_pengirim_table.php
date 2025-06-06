<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameKurirToNamaKurirInPengirimTable extends Migration
{
    public function up()
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->renameColumn('kurir', 'nama_kurir');
        });
    }

    public function down()
    {
        Schema::table('pengiriman', function (Blueprint $table) {
            $table->renameColumn('nama_kurir', 'kurir');
        });
    }
}
