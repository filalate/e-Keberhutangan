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
        Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('pegawai'); // Default Pegawai
        $table->string('negeri')->nullable(); // Negeri Pegawai & Admin Negeri
        $table->boolean('verified')->default(false); // Untuk verify Admin Negeri
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'negeri', 'verified']);
        });
    }

};
