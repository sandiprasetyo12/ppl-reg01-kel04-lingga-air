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
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket')->nullable()->unique();
            $table->string('kode_desa', 15);
            $table->foreign('kode_desa')->references('kode_desa')->on('desas')->cascadeOnDelete();
            $table->foreignId('simulasi_id')->nullable()->constrained('simulasi_kelayakan')->nullOnDelete();
            $table->enum('status', [
                'Draf',
                'Diajukan',
                'Dalam Telaah',
                'Disetujui',
                'Ditolak',
                'Revisi',
                'Dijadwalkan',
                'Selesai'
            ])->default('Draf');
            $table->unsignedInteger('jumlah_kk');
            $table->unsignedInteger('hari_tanpa_air');
            $table->unsignedInteger('jarak_sumber_air'); // dalam meter
            $table->text('alasan_pengajuan');
            $table->text('lokasi_pengantaran')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};

