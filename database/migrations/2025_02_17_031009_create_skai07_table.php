<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('skai07', function (Blueprint $table) {
            $table->id();
            
            // Info Pegawai
            $table->string('nama');
            $table->string('no_kad_pengenalan');
            $table->string('no_badan');
            $table->string('gred');
            $table->string('jawatan');

            // Pendapatan
            $table->decimal('gaji', 10, 2)->default(0);
            $table->decimal('elaun', 10, 2)->default(0);
            $table->decimal('sewa_rumah', 10, 2)->default(0);
            $table->decimal('sewa_kenderaan', 10, 2)->default(0);
            $table->decimal('sumbangan_suami_isteri', 10, 2)->default(0);
            $table->decimal('lain_lain_pendapatan', 10, 2)->default(0);

            // Liabiliti Bercagar
            $table->decimal('rumah', 10, 2)->default(0);
            $table->decimal('kereta', 10, 2)->default(0);
            $table->decimal('motorsikal', 10, 2)->default(0);
            $table->decimal('komputer', 10, 2)->default(0);
            $table->decimal('tabung_haji', 10, 2)->default(0);
            $table->decimal('asb', 10, 2)->default(0);
            $table->decimal('simpanan', 10, 2)->default(0);
            $table->decimal('zakat', 10, 2)->default(0);
            $table->decimal('lain2_bercagar', 10, 2)->default(0);

            // Liabiliti Tidak Bercagar
            $table->decimal('pinjaman_peribadi', 10, 2)->default(0);
            $table->decimal('kad_kredit', 10, 2)->default(0);
            $table->decimal('lain2_tidak_bercagar', 10, 2)->default(0);

            // Ringkasan Kewangan
            $table->decimal('jumlah_pendapatan', 10, 2)->default(0);
            $table->decimal('jumlah_perbelanjaan', 10, 2)->default(0);
            $table->decimal('lebihan_pendapatan', 10, 2)->default(0);
            $table->decimal('percent_liabiliti_tidak_bercagar', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('skai07');
    }
};
