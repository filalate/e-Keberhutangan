<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGredToPenyataGajiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->string('gred', 50)->after('nama_pegawai'); // Tambah kolum 'gred' selepas kolum 'nama_pegawai'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->dropColumn('gred'); // Hapuskan kolum 'gred' jika migrasi dibatalkan
        });
    }
}
