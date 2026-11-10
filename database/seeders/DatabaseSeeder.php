<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil seeder master desa Purbalingga (dari branch main)
        $this->call([DesaSeeder::class]);

        if (app()->environment(['local', 'testing'])) {
            $this->call([DesaDemografiDummySeeder::class]);
        }

        // 2. Admin BPBD Purbalingga
        User::firstOrCreate(
            ['email' => 'admin@bpbd.purbalinggakab.go.id'],
            [
                'name' => 'Admin BPBD Purbalingga',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0281-891011',
            ]
        );

        // 3. User Pemohon Desa Serang (Akun default desa)
        $userSerang = User::firstOrCreate(
            ['email' => 'desa.serang@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Serang',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081398765432',
            ]
        );

        $desaSerang = Desa::where('nama_desa', 'like', '%Serang%')->first();
        if ($desaSerang) {
            $desaSerang->update([
                'user_id' => $userSerang->id,
                'nama_pj_darurat' => 'Agus Hendra',
                'no_wa_darurat' => '081398765432',
                'titik_drop_air' => 'Posko Mandiri RT 01 RW 04 Desa Serang',
            ]);
        }

        // 4. User Pemohon Desa Bodas
        $userBodas = User::firstOrCreate(
            ['email' => 'desa.bodas@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Bodas',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081543219876',
            ]
        );

        $desaBodas = Desa::where('nama_desa', 'like', '%Bodas%')->first();
        if ($desaBodas) {
            $desaBodas->update([
                'user_id' => $userBodas->id,
                'nama_pj_darurat' => 'Slamet Riyadi',
                'no_wa_darurat' => '081543219876',
                'titik_drop_air' => 'Depan Balai Pertemuan RW 02',
            ]);
        }

        // 5. User Pemohon Desa Tanalum
        $userTanalum = User::firstOrCreate(
            ['email' => 'desa.tanalum@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Tanalum',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081234567890',
            ]
        );

        $desaTanalum = Desa::where('nama_desa', 'like', '%Tanalum%')->first();
        if ($desaTanalum) {
            $desaTanalum->update([
                'user_id' => $userTanalum->id,
                'nama_pj_darurat' => 'Budi Santoso',
                'no_wa_darurat' => '081234567890',
                'titik_drop_air' => 'Halaman Balai Desa Tanalum, Dusun Krajan RT 02 / RW 03',
            ]);
        }
    }
}
