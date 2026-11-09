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
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->unique(); // contoh: TKT-202611-001
            $table->foreignId('desa_id')->constrained('desas')->onDelete('cascade');
            $table->enum('status', ['Menunggu Verifikasi', 'Disetujui', 'Ditolak', 'Revisi'])->default('Menunggu Verifikasi');
            
            // Kondisi Lapangan (Persis sesuai Form Fitur 2 Cek Kelayakan)
            $table->integer('kk_terdampak');                      // Tidak boleh melebihi KK total
            $table->integer('hari_tanpa_air');                    // Maksimum normalisasi pada 7 hari
            $table->integer('jarak_sumber_air');                  // Dalam meter, maks normalisasi pada 2.000 meter
            $table->integer('jiwa_rentan')->default(0);           // Akumulasi balita + lansia + disabilitas terdampak
            $table->integer('kebutuhan_tangki')->default(1);
            $table->text('alasan_permohonan')->nullable();

            // Hasil Perhitungan AHP-SAW (Kunci PBI-010)
            $table->decimal('skor_ahp_saw', 5, 2);                // Rentang 0 - 100
            $table->enum('status_kelayakan', ['Kritis', 'Waspada', 'Aman'])->default('Kritis');
            $table->json('rincian_kriteria')->nullable();         // Detail nilai C1, C2, C3, C4

            // Berkas pendukung
            $table->string('surat_kades')->nullable();
            $table->string('foto_lapangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};
