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
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_desa', 10)->unique();
            $table->string('nama_desa');
            $table->string('kecamatan');
            $table->unsignedInteger('jumlah_kk')->default(0);
            $table->unsignedInteger('jumlah_jiwa')->default(0);
            $table->unsignedInteger('jumlah_balita')->default(0);
            $table->unsignedInteger('jumlah_lansia')->default(0);
            $table->unsignedInteger('jumlah_disabilitas')->default(0);
            $table->string('nama_pj')->nullable();
            $table->string('no_wa')->nullable();
            $table->text('lokasi_drop_point')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa');
    }
};

