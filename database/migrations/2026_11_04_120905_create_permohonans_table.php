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
        
        // Data kondisi kekeringan desa (parameter PBI-010)
        $table->integer('kk_terdampak');
        $table->integer('jiwa_rentan');
        $table->integer('hari_tanpa_air');
        $table->decimal('jarak_sumber_air', 8, 2); // dalam km
        $table->integer('kebutuhan_tangki')->default(1);
        $table->text('alasan_permohonan')->nullable();

        // Hasil Perhitungan AHP-SAW (Kunci PBI-010)
        $table->decimal('skor_ahp_saw', 5, 2); // contoh: 92.40
        $table->enum('status_kelayakan', ['Kritis', 'Waspada', 'Aman'])->default('Kritis');
        $table->json('rincian_kriteria')->nullable(); // detail kalkulasi 4 kriteria

        // Berkas pendukung (Kunci PBI-010)
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
