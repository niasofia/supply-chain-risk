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
    Schema::create('countries', function (Blueprint $table) {
        $table->id();
        $table->string('name');          // Contoh: Germany, China, Indonesia
        $table->string('iso_code', 3);   // Contoh: DEU, CHN, IDN
        $table->string('currency');      // Contoh: EUR, CNY, IDR
        $table->double('gdp')->nullable();
        $table->double('inflation')->nullable();
        $table->double('population')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
