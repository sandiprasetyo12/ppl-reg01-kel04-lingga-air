<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\RiwayatCekKelayakan;
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

        // 3. User Pemohon Desa Serang & Riwayat Cek Kelayakan (WASPADA)
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

            $riwayatSerang = RiwayatCekKelayakan::firstOrCreate(
                [
                    'kode_desa' => $desaSerang->kode_desa,
                    'status' => 'waspada',
                ],
                [
                    'jumlah_kk_total' => $desaSerang->jumlah_kk_total ?: 750,
                    'jumlah_jiwa_total' => $desaSerang->jumlah_jiwa_total ?: 2800,
                    'jumlah_balita' => $desaSerang->jumlah_balita ?: 110,
                    'jumlah_lansia' => $desaSerang->jumlah_lansia ?: 90,
                    'jumlah_disabilitas' => $desaSerang->jumlah_disabilitas ?: 15,
                    'kk_terdampak' => 85,
                    'hari_tanpa_air' => 8,
                    'jarak_sumber_meter' => 800,
                    'c1' => 0.113333,
                    'c2' => 0.511905,
                    'c3' => 1.0,
                    'c4' => 0.4,
                    'bobot_c1' => 0.35,
                    'bobot_c2' => 0.25,
                    'bobot_c3' => 0.25,
                    'bobot_c4' => 0.15,
                    'rasio_konsistensi' => 0.02,
                    'skor' => 74.80,
                ]
            );

            Pengajuan::firstOrCreate(
                [
                    'kode_desa' => $desaSerang->kode_desa,
                    'status' => 'Draf',
                ],
                [
                    'riwayat_cek_kelayakan_id' => $riwayatSerang->id,
                    'jumlah_kk' => 85,
                    'hari_tanpa_air' => 8,
                    'jarak_sumber_air' => 800,
                    'alasan_pengajuan' => 'Mata air desa mengalami penurunan debit drastis selama musim kemarau.',
                    'lokasi_pengantaran' => 'Posko Mandiri RT 01 RW 04 Desa Serang',
                    'latitude' => $desaSerang->latitude,
                    'longitude' => $desaSerang->longitude,
                ]
            );
        }

        // 4. User Pemohon Desa Bodas & Riwayat Cek Kelayakan (AMAN)
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

            RiwayatCekKelayakan::firstOrCreate(
                [
                    'kode_desa' => $desaBodas->kode_desa,
                    'status' => 'aman',
                ],
                [
                    'jumlah_kk_total' => $desaBodas->jumlah_kk_total ?: 400,
                    'jumlah_jiwa_total' => $desaBodas->jumlah_jiwa_total ?: 1500,
                    'jumlah_balita' => $desaBodas->jumlah_balita ?: 45,
                    'jumlah_lansia' => $desaBodas->jumlah_lansia ?: 30,
                    'jumlah_disabilitas' => $desaBodas->jumlah_disabilitas ?: 5,
                    'kk_terdampak' => 20,
                    'hari_tanpa_air' => 2,
                    'jarak_sumber_meter' => 200,
                    'c1' => 0.05,
                    'c2' => 0.35,
                    'c3' => 0.285,
                    'c4' => 0.1,
                    'bobot_c1' => 0.35,
                    'bobot_c2' => 0.25,
                    'bobot_c3' => 0.25,
                    'bobot_c4' => 0.15,
                    'rasio_konsistensi' => 0.02,
                    'skor' => 48.10,
                ]
            );
        }

        // 5. User Pemohon Desa Tanalum & Riwayat Cek Kelayakan (KRITIS)
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

            RiwayatCekKelayakan::firstOrCreate(
                [
                    'kode_desa' => $desaTanalum->kode_desa,
                    'status' => 'kritis',
                ],
                [
                    'jumlah_kk_total' => $desaTanalum->jumlah_kk_total ?: 600,
                    'jumlah_jiwa_total' => $desaTanalum->jumlah_jiwa_total ?: 2400,
                    'jumlah_balita' => $desaTanalum->jumlah_balita ?: 95,
                    'jumlah_lansia' => $desaTanalum->jumlah_lansia ?: 80,
                    'jumlah_disabilitas' => $desaTanalum->jumlah_disabilitas ?: 12,
                    'kk_terdampak' => 120,
                    'hari_tanpa_air' => 14,
                    'jarak_sumber_meter' => 1200,
                    'c1' => 0.2,
                    'c2' => 0.52,
                    'c3' => 1.0,
                    'c4' => 0.6,
                    'bobot_c1' => 0.35,
                    'bobot_c2' => 0.25,
                    'bobot_c3' => 0.25,
                    'bobot_c4' => 0.15,
                    'rasio_konsistensi' => 0.02,
                    'skor' => 92.40,
                ]
            );
        }
    }
}
