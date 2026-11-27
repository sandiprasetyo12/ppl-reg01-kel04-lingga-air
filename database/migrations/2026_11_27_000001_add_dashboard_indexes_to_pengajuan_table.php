<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->index('status', 'pengajuan_status_idx');
            $table->index('tanggal_pengajuan', 'pengajuan_tanggal_idx');
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropIndex('pengajuan_status_idx');
            $table->dropIndex('pengajuan_tanggal_idx');
        });
    }
};
