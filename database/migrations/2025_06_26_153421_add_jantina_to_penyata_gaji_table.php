<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->string('jantina')->after('nama_pegawai'); // Add gender column after 'nama_pegawai'
        });
    }

    public function down()
    {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->dropColumn('jantina');
        });
    }

};
