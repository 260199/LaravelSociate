<?php

use App\Models\Renstra;
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
        Schema::create('pilars', function (Blueprint $table) {
            $table->id('PilarID');
            $table->unsignedBigInteger('RenstraID');
            $table->foreign('RenstraID')->references('RenstraID')->on('renstras');
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
        Schema::dropIfExists('pilars');
    }
};
