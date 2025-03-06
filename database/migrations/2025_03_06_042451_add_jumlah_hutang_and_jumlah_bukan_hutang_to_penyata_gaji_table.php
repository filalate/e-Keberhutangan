<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->decimal('jumlah_hutang', 10, 3)->default(0); // 10 digit, 3 decimal places
            $table->decimal('jumlah_bukan_hutang', 10, 3)->default(0); // 10 digit, 3 decimal places
        });
    }
    
    public function down() {
        Schema::table('penyata_gaji', function (Blueprint $table) {
            $table->dropColumn('jumlah_hutang');
            $table->dropColumn('jumlah_bukan_hutang');
        });
    }
};
