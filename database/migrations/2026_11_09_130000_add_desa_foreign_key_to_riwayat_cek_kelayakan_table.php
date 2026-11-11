<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_cek_kelayakan', function (Blueprint $table) {
            $table->string('kode_desa', 15)->nullable()->change();
            $table->foreign('kode_desa')
                ->references('kode_desa')
                ->on('desas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_cek_kelayakan', function (Blueprint $table) {
            $table->dropForeign(['kode_desa']);
            $table->string('kode_desa')->nullable()->change();
        });
    }
};
