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
        if (!Schema::hasTable('penyalurans')) {
            Schema::create('penyalurans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_penyaluran', 30)->unique();
                $table->string('nomor_tiket', 50)->nullable()->index();
                $table->string('kode_desa', 20)->index();
                $table->foreignId('penyalur_id')->nullable()->constrained('penyalurs')->nullOnDelete();
                $table->date('tanggal_penyaluran');
                $table->integer('volume_air_liter')->default(5000);
                $table->integer('jumlah_tangki')->default(1);
                $table->enum('status', ['Terkirim', 'Dalam Pengiriman', 'Dijadwalkan', 'Kendala'])->default('Terkirim');
                $table->string('titik_serah_terima')->nullable();
                $table->string('nama_penerima')->nullable();
                $table->string('no_telepon_penerima', 25)->nullable();
                $table->string('armada_nopol', 20)->nullable();
                $table->string('nama_driver')->nullable();
                $table->text('catatan')->nullable();
                $table->string('foto_bukti')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyalurans');
    }
};
