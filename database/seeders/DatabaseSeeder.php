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
        // Panggil seeder master desa Purbalingga
        $this->call([DesaSeeder::class]);

        if (app()->environment(['local', 'testing'])) {
            $this->call([DesaDemografiDummySeeder::class]);
        }
    }
}
