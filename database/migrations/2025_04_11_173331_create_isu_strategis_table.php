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
        Schema::create('isustrategis', function (Blueprint $table) {
            $table->id('IsuID');
            $table->unsignedBigInteger('PilarID');
            $table->foreign('PilarID')->references('PilarID')->on('pilars');
            $table->string('nama');
            $table->enum('NA', ['Y','N'])->default('N');
            $table->dateTime('DCreated');
            $table->unsignedBigInteger('UCreated')->nullable();
            $table->dateTime('DEdited')->nullable();
            $table->unsignedBigInteger('UEdited')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('isu_strategis');
    }
};
