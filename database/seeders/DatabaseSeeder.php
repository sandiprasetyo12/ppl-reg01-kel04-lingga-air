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
        // 1. Panggil seeder master 239 desa Purbalingga
        $this->call([DesaSeeder::class]);

        // 2. Panggil seeder akun admin BPBD
        $this->call([BpbdAdminSeeder::class]);

        // 3. Akun perwakilan Desa Serang (tertaut ke Desa Serang untuk simulasi login desa)
        $userSerang = User::firstOrCreate(
            ['username' => 'desa_serang'],
            [
                'password' => Hash::make('password123'),
                'role' => 'desa',
                'phone' => '081398765432',
            ]
        );

        $desaSerang = Desa::where('nama_desa', 'like', '%Serang%')->first();
        if ($desaSerang) {
            $desaSerang->update([
                'user_id' => $userSerang->id,
                'nama_pj_darurat' => 'Agus Hendra',
                'no_wa_darurat' => '081398765432',
                'titik_drop_air' => null,
                'jumlah_kk_total' => $desaSerang->jumlah_kk_total ?: 750,
                'jumlah_jiwa_total' => $desaSerang->jumlah_jiwa_total ?: 2800,
                'jumlah_balita' => $desaSerang->jumlah_balita ?: 110,
                'jumlah_lansia' => $desaSerang->jumlah_lansia ?: 90,
                'jumlah_disabilitas' => $desaSerang->jumlah_disabilitas ?: 15,
            ]);
        }
    }
}
