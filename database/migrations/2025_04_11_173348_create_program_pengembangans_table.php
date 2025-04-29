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
        Schema::create('program_pengembangans', function (Blueprint $table) {
            $table->id('ProgramPengembanganID');
            $table->unsignedBigInteger('IsuID');
            $table->foreign('IsuID')->references('IsuID')->on('isustrategis');
            $table->string('nama');
            $table->enum('NA', ['Y','N'])->default('N');
            $table->dateTime('DCreated')->nullable();
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
        Schema::dropIfExists('program_pengembangans');
    }
};
