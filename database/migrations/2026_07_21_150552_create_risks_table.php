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
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->string('location'); // Contoh: Pelabuhan Belawan, Port of Hamburg
            $table->string('category'); // Kongesti Logistik, Cuaca Ekstrem, Operasional Alat, Keamanan
            $table->string('indicator'); // Indikator Risiko
            $table->enum('risk_level', ['LOW', 'MEDIUM', 'HIGH'])->default('LOW');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};