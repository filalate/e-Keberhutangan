<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('penyata_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai');
            
            // Hutang
            $table->decimal('pinjaman_peribadi_bsn', 10, 2)->default(0);
            $table->decimal('pinjaman_perumahan', 10, 2)->default(0);
            $table->decimal('bayaran_balik_itp', 10, 2)->default(0);
            $table->decimal('bayaran_balik_bsh', 10, 2)->default(0);
            $table->decimal('ptptn', 10, 2)->default(0);
            $table->decimal('kutipan_semula_emolumen', 10, 2)->default(0);
            $table->decimal('arahan_potongan_nafkah', 10, 2)->default(0);
            $table->decimal('komputer', 10, 2)->default(0);
            $table->decimal('pcb', 10, 2)->default(0);
            $table->decimal('lain_lain_potongan_pembentungan', 10, 2)->default(0);
            $table->decimal('koperasi', 10, 2)->default(0);
            $table->decimal('berkat', 10, 2)->default(0);
            $table->decimal('angkasa', 10, 2)->default(0);

            // Bukan Hutang
            $table->decimal('potongan_lembaga_th', 10, 2)->default(0);
            $table->decimal('amanah_saham_nasional', 10, 2)->default(0);
            $table->decimal('zakat_yayasan_wakaf', 10, 2)->default(0);
            $table->decimal('insuran', 10, 2)->default(0);
            $table->decimal('kwsp', 10, 2)->default(0);
            $table->decimal('i_destinasi', 10, 2)->default(0);
            $table->decimal('angkasa_bukan_pinjaman', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('penyata_gaji');
    }
};
