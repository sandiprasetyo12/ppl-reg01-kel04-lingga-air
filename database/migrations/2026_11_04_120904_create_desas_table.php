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
        $table->integer('jumlah_kk')->default(0);
        $table->integer('jumlah_jiwa')->default(0);
        $table->integer('kelompok_rentan')->default(0);
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
