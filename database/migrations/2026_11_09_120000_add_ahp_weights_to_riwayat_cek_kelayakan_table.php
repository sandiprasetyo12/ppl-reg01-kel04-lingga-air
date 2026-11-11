<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('riwayat_cek_kelayakan', function (Blueprint $table) {
            $table->decimal('bobot_c1', 8, 6)->nullable();
            $table->decimal('bobot_c2', 8, 6)->nullable();
            $table->decimal('bobot_c3', 8, 6)->nullable();
            $table->decimal('bobot_c4', 8, 6)->nullable();
            $table->decimal('rasio_konsistensi', 8, 6)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('riwayat_cek_kelayakan', function (Blueprint $table) {
            $table->dropColumn([
                'bobot_c1',
                'bobot_c2',
                'bobot_c3',
                'bobot_c4',
                'rasio_konsistensi',
            ]);
        });
    }
};
