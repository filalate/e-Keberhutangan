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
        Schema::table('skai07', function (Blueprint $table) {
            // Add the user_id column
            $table->unsignedBigInteger('user_id'); // Removed nullable() for required user_id
    
            // Add the foreign key constraint (referencing the 'id' column in 'users' table)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Optional: Add an index on user_id for performance
            $table->index('user_id');
        });
    }
    
    public function down()
    {
        Schema::table('skai07', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['user_id']);
            
            // Drop the user_id column
            $table->dropColumn('user_id');
        });
    }
};
