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
    Schema::create('keputusan_permohonans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('permohonan_id')->constrained('permohonans')->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Admin verifikator
        $table->enum('status_keputusan', ['Disetujui', 'Ditolak', 'Revisi']);
        $table->integer('volume_air_disetujui')->nullable(); // dalam liter
        $table->text('catatan_alasan'); // wajib diisi jika ditolak / minta revisi (Audit Trail)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keputusan_permohonans');
    }
};
