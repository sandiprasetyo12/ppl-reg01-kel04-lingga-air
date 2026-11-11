<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_cek_kelayakan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_desa', 15)->nullable();
            $table->integer('jumlah_kk_total');
            $table->integer('jumlah_jiwa_total');
            $table->integer('jumlah_balita');
            $table->integer('jumlah_lansia');
            $table->integer('jumlah_disabilitas');
            $table->integer('kk_terdampak');
            $table->integer('hari_tanpa_air');
            $table->integer('jarak_sumber_meter');
            $table->decimal('c1', 8, 6);
            $table->decimal('c2', 8, 6);
            $table->decimal('c3', 8, 6);
            $table->decimal('c4', 8, 6);
            $table->decimal('skor', 5, 2);
            $table->string('status', 16);
            $table->timestamps();

            $table->index(['kode_desa', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_cek_kelayakan');
    }
};
