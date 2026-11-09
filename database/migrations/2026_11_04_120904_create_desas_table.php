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
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->string('penanggung_jawab')->nullable();
            $table->string('kontak_wa')->nullable();
            
            // Data Acuan Desa (Sinkron dengan Fitur 2 & Fitur 1)
            $table->integer('jumlah_kk')->default(0);           // Jumlah KK total
            $table->integer('jumlah_jiwa')->default(0);         // Jumlah jiwa total
            $table->integer('jumlah_balita')->default(0);       // Kelompok rentan: balita
            $table->integer('jumlah_lansia')->default(0);       // Kelompok rentan: lansia
            $table->integer('jumlah_disabilitas')->default(0);  // Kelompok rentan: disabilitas
            
            // Titik Logistik
            $table->string('titik_drop_air')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desas');
    }
};
