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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); 
            $table->unsignedBigInteger('followeduser');
            $table->foreign('followeduser')->references('id')->on('users');
            $table->timestamps();
        });
    }
    //who is the user whos doing the following | constrained prevents creation of the row if the id is invalid
    // yung followuser is ay id galing sa users table
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
