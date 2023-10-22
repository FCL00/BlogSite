<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //to add some new column 
        // Schema::table("users", function($table){
        //     $table->string("color");
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // rollback if we dont like the migration
        // Schema::table("users", function($table){
        //     $table->dropColumn("color");
        // });
    }
};
