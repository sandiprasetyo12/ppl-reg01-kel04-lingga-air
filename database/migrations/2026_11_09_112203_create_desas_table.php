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
            // Primary key untuk kode desa
            $table->string('kode_desa', 15)->primary();
            
            // Foreign key untuk relasi ke user (Desa)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Identitas Wilayah
            $table->string('nama_desa', 100);
            $table->string('kecamatan', 100);

            // Data Kependudukan & Kelompok Rentan (Fitur 1 & 2)
            $table->integer('jumlah_kk_total')->nullable();
            $table->integer('jumlah_jiwa_total')->nullable();
            $table->integer('jumlah_balita')->nullable();
            $table->integer('jumlah_lansia')->nullable();
            $table->integer('jumlah_disabilitas')->nullable();

            // Titik Lokasi Drop Air & Koordinat Spasial GIS (Fitur 9)
            $table->string('titik_drop_air', 150)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Kontak Darurat Lapangan (Fitur 1 & 6 Distribusi)
            $table->string('nama_pj_darurat', 100)->nullable();
            $table->string('no_wa_darurat', 20)->nullable();

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