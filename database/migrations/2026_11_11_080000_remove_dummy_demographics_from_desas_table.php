<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('desas', 'data_kependudukan_dummy')) {
            DB::table('desas')
                ->where('data_kependudukan_dummy', true)
                ->update([
                    'jumlah_kk_total' => null,
                    'jumlah_jiwa_total' => null,
                    'jumlah_balita' => null,
                    'jumlah_lansia' => null,
                    'jumlah_disabilitas' => null,
                ]);

            Schema::table('desas', function (Blueprint $table) {
                $table->dropColumn('data_kependudukan_dummy');
            });
        }
    }

    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->boolean('data_kependudukan_dummy')->default(false);
        });
    }
};
