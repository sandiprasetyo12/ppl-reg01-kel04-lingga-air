<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder master desa Purbalingga dan seeder akun admin BPBD
        $this->call([DesaSeeder::class]);
        $this->call([BpbdAdminSeeder::class]);
    }
}