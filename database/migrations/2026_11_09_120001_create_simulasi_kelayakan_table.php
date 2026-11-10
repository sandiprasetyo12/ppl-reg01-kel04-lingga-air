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
        Schema::create('simulasi_kelayakan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_desa', 15);
            $table->foreign('kode_desa')->references('kode_desa')->on('desas')->cascadeOnDelete();
            $table->unsignedInteger('kk_terdampak');
            $table->unsignedInteger('jiwa_rentan')->default(0);
            $table->unsignedInteger('hari_tanpa_air');
            $table->unsignedInteger('jarak_sumber_air'); // dalam meter
            $table->decimal('skor_ahp_saw', 5, 2);
            $table->enum('status_kelayakan', ['Kritis', 'Waspada', 'Aman']);
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulasi_kelayakan');
    }
};
