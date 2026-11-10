<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->boolean('data_kependudukan_dummy')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn('data_kependudukan_dummy');
        });
    }
};
