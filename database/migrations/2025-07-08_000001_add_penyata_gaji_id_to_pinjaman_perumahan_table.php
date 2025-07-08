<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pinjaman_perumahan', function (Blueprint $table) {
            $table->unsignedBigInteger('penyata_gaji_id')->after('agregat_bersih');
        });
    }

    public function down()
    {
        Schema::table('pinjaman_perumahan', function (Blueprint $table) {
            $table->dropColumn('penyata_gaji_id');
        });
    }
};
