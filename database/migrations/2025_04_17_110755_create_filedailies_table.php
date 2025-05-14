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
        Schema::create('filedailies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_id');
            $table->foreign('daily_id')->references('id')->on('dailies')->onDelete('cascade');
            $table->string('image_path'); 
            $table->string('desc')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filedailies');
    }
};
