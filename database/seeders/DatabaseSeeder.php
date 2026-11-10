<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\SimulasiKelayakan;
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

        // 2. Admin BPBD Purbalingga
        $admin = User::firstOrCreate(
            ['email' => 'admin@bpbd.purbalinggakab.go.id'],
            [
                'name' => 'Admin BPBD Purbalingga',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0281-891011',
            ]
        );

        // 3. Desa Serang (Kecamatan Karangreja) - SIMULASI STATUS WASPADA (Skor 74.80)
        $userSerang = User::firstOrCreate(
            ['email' => 'desa.serang@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Serang',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081398765432',
            ]
        );

        $desaSerang = Desa::where('nama_desa', 'Serang')->first()
            ?: Desa::firstOrCreate(['kode_desa' => '33.03.10.2001'], ['nama_desa' => 'Serang', 'kecamatan' => 'Karangreja']);

        $desaSerang->update([
            'user_id' => $userSerang->id,
            'nama_desa' => 'Desa Serang',
            'kecamatan' => 'Karangreja',
            'jumlah_kk_total' => 750,
            'jumlah_jiwa_total' => 2800,
            'jumlah_balita' => 110,
            'jumlah_lansia' => 90,
            'jumlah_disabilitas' => 15,
            'nama_pj_darurat' => 'Agus Hendra',
            'no_wa_darurat' => '081398765432',
            'titik_drop_air' => 'Posko Mandiri RT 01 RW 04 Desa Serang',
            'latitude' => $desaSerang->latitude ?: -7.244225,
            'longitude' => $desaSerang->longitude ?: 109.279768,
        ]);

        SimulasiKelayakan::firstOrCreate(
            [
                'kode_desa' => $desaSerang->kode_desa,
                'status_kelayakan' => 'Waspada',
            ],
            [
                'kk_terdampak' => 85,
                'jiwa_rentan' => 25,
                'hari_tanpa_air' => 8,
                'jarak_sumber_air' => 800,
                'skor_ahp_saw' => 74.80,
                'rekomendasi' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
            ]
        );

        // 4. Desa Bodas / Danasari - SIMULASI STATUS AMAN (Skor 48.10)
        // Digunakan untuk pengujian: status Aman tidak boleh membuat pengajuan dan langsung memunculkan pop-up modal
        $userBodas = User::firstOrCreate(
            ['email' => 'desa.bodas@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Bodas',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081543219876',
            ]
        );

        $desaBodas = Desa::where('nama_desa', 'like', '%Bodas%')->first()
            ?: (Desa::where('kecamatan', 'Karangjambu')->first()
                ?: Desa::firstOrCreate(['kode_desa' => '33.03.17.2001'], ['nama_desa' => 'Desa Bodas', 'kecamatan' => 'Karangjambu']));

        $desaBodas->update([
            'user_id' => $userBodas->id,
            'nama_desa' => 'Desa Bodas',
            'kecamatan' => 'Karangjambu',
            'jumlah_kk_total' => 400,
            'jumlah_jiwa_total' => 1500,
            'jumlah_balita' => 45,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
            'nama_pj_darurat' => 'Slamet Riyadi',
            'no_wa_darurat' => '081543219876',
            'titik_drop_air' => 'Depan Balai Pertemuan RW 02',
            'latitude' => $desaBodas->latitude ?: -7.220100,
            'longitude' => $desaBodas->longitude ?: 109.410200,
        ]);

        SimulasiKelayakan::firstOrCreate(
            [
                'kode_desa' => $desaBodas->kode_desa,
                'status_kelayakan' => 'Aman',
            ],
            [
                'kk_terdampak' => 20,
                'jiwa_rentan' => 5,
                'hari_tanpa_air' => 2,
                'jarak_sumber_air' => 200,
                'skor_ahp_saw' => 48.10,
                'rekomendasi' => 'Kondisi pasokan air masih terkendali secara mandiri di tingkat lingkungan.',
            ]
        );

        // 5. Desa Tanalum (Kecamatan Rembang) - SIMULASI STATUS KRITIS (Skor 92.40)
        $userTanalum = User::firstOrCreate(
            ['email' => 'desa.tanalum@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Tanalum',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081234567890',
            ]
        );

        $desaTanalum = Desa::where('nama_desa', 'like', '%Tanalum%')->first()
            ?: Desa::firstOrCreate(['kode_desa' => '33.03.13.2011'], ['nama_desa' => 'Desa Tanalum', 'kecamatan' => 'Rembang']);

        $desaTanalum->update([
            'user_id' => $userTanalum->id,
            'nama_desa' => 'Desa Tanalum',
            'kecamatan' => 'Rembang',
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'nama_pj_darurat' => 'Budi Santoso',
            'no_wa_darurat' => '081234567890',
            'titik_drop_air' => 'Halaman Balai Desa Tanalum, Dusun Krajan RT 02 / RW 03',
            'latitude' => $desaTanalum->latitude ?: -7.268235,
            'longitude' => $desaTanalum->longitude ?: 109.534706,
        ]);

        $simulasiTanalum = SimulasiKelayakan::firstOrCreate(
            [
                'kode_desa' => $desaTanalum->kode_desa,
                'status_kelayakan' => 'Kritis',
            ],
            [
                'kk_terdampak' => 120,
                'jiwa_rentan' => 40,
                'hari_tanpa_air' => 14,
                'jarak_sumber_air' => 1200,
                'skor_ahp_saw' => 92.40,
                'rekomendasi' => 'Segera ajukan pasokan air bersih darurat untuk kebutuhan 120 KK terdampak.',
            ]
        );

        // Draf contoh untuk Desa Tanalum
        Pengajuan::firstOrCreate(
            [
                'kode_desa' => $desaTanalum->kode_desa,
                'status' => 'Draf',
            ],
            [
                'nomor_tiket' => null,
                'simulasi_id' => $simulasiTanalum->id,
                'jumlah_kk' => 120,
                'hari_tanpa_air' => 14,
                'jarak_sumber_air' => 1200,
                'alasan_pengajuan' => 'Sumber air sumur gali dan mata air desa mengering total selama dua minggu terakhir akibat kemarau panjang. Warga terpaksa membeli air pikulan.',
                'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum, Dusun Krajan',
                'latitude' => $desaTanalum->latitude ?: -7.268235,
                'longitude' => $desaTanalum->longitude ?: 109.534706,
                'catatan_tambahan' => 'Akses mobil tangki muat melalui gerbang utama balai desa. Jalan beraspal lebar 4 meter.',
            ]
        );
    }
}
