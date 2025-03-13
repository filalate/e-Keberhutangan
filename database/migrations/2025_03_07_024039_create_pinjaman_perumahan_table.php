<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePinjamanPerumahanTable extends Migration
{
    public function up()
    {
        Schema::create('pinjaman_perumahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            $table->string('no_ic');
            $table->string('jawatan');
            $table->string('gred');
            $table->string('tempat_bertugas');
            $table->decimal('jumlah_pendapatan', 10, 2);
            $table->decimal('jumlah_potongan', 10, 2);
            $table->decimal('agregat_keterhutangan', 5, 2); // percentage
            $table->decimal('jumlah_pinjaman_perumahan', 10, 2);
            $table->decimal('agregat_bersih', 5, 2); // percentage
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pinjaman_perumahan');
    }
}
