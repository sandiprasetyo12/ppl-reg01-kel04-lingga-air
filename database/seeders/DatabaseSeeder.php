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
        // 1. Admin BPBD Purbalingga
        $admin = User::firstOrCreate(
            ['email' => 'admin@bpbd.purbalinggakab.go.id'],
            [
                'name' => 'Admin BPBD Purbalingga',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0281-891011',
            ]
        );

        // 2. Pemohon Desa Tanalum (Kecamatan Rembang) - Sesuai Mockup Proposal Halaman 47, 55, 56, 57
        $userTanalum = User::firstOrCreate(
            ['email' => 'desa.tanalum@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Tanalum',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081234567890',
            ]
        );

        $desaTanalum = Desa::firstOrCreate(
            ['kode_desa' => '3303132005'],
            [
                'user_id' => $userTanalum->id,
                'nama_desa' => 'Desa Tanalum',
                'kecamatan' => 'Rembang',
                'jumlah_kk' => 600,
                'jumlah_jiwa' => 2400,
                'jumlah_balita' => 95,
                'jumlah_lansia' => 80,
                'jumlah_disabilitas' => 12,
                'nama_pj' => 'Budi Santoso',
                'no_wa' => '081234567890',
                'lokasi_drop_point' => 'Halaman Balai Desa Tanalum, Dusun Krajan RT 02 / RW 03',
                'latitude' => -7.2845100,
                'longitude' => 109.4321000,
            ]
        );

        // Simulasi Kelayakan Kritis untuk Desa Tanalum (Skor 92.40 - Kritis)
        $simulasiTanalum = SimulasiKelayakan::create([
            'desa_id' => $desaTanalum->id,
            'kk_terdampak' => 120,
            'jiwa_rentan' => 40,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'skor_ahp_saw' => 92.40,
            'status_kelayakan' => 'Kritis',
            'rekomendasi' => 'Segera ajukan pasokan air bersih darurat untuk kebutuhan 120 KK terdampak.',
        ]);

        // Draf awal untuk Desa Tanalum
        $drafTanalum = Pengajuan::create([
            'nomor_tiket' => null, // Belum dikirim
            'desa_id' => $desaTanalum->id,
            'simulasi_id' => $simulasiTanalum->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Sumber air sumur gali dan mata air desa mengering total selama dua minggu terakhir akibat kemarau panjang. Warga terpaksa membeli air pikulan.',
            'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum, Dusun Krajan',
            'latitude' => -7.2845100,
            'longitude' => 109.4321000,
            'catatan_tambahan' => 'Akses mobil tangki muat melalui gerbang utama balai desa. Jalan beraspal lebar 4 meter.',
            'tanggal_pengajuan' => null,
        ]);

        // 3. Pemohon Desa Serang (Kecamatan Karangreja) - Status Waspada
        $userSerang = User::firstOrCreate(
            ['email' => 'desa.serang@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Serang',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081398765432',
            ]
        );

        $desaSerang = Desa::firstOrCreate(
            ['kode_desa' => '3303082001'],
            [
                'user_id' => $userSerang->id,
                'nama_desa' => 'Desa Serang',
                'kecamatan' => 'Karangreja',
                'jumlah_kk' => 750,
                'jumlah_jiwa' => 2800,
                'jumlah_balita' => 110,
                'jumlah_lansia' => 90,
                'jumlah_disabilitas' => 15,
                'nama_pj' => 'Agus Hendra',
                'no_wa' => '081398765432',
                'lokasi_drop_point' => 'Posko Mandiri RT 01 RW 04 Desa Serang',
                'latitude' => -7.2512000,
                'longitude' => 109.3120000,
            ]
        );

        $simulasiSerang = SimulasiKelayakan::create([
            'desa_id' => $desaSerang->id,
            'kk_terdampak' => 85,
            'jiwa_rentan' => 25,
            'hari_tanpa_air' => 8,
            'jarak_sumber_air' => 800,
            'skor_ahp_saw' => 74.80,
            'status_kelayakan' => 'Waspada',
            'rekomendasi' => 'Penuhi syarat administrasi dan siapkan titik tandon untuk pengajuan bantuan pasokan.',
        ]);

        // 4. Pemohon Desa Bodas (Status Aman) untuk tes penolakan pengiriman status Aman
        $userBodas = User::firstOrCreate(
            ['email' => 'desa.bodas@purbalinggakab.go.id'],
            [
                'name' => 'Pemerintah Desa Bodas',
                'password' => Hash::make('password'),
                'role' => 'pemohon',
                'phone' => '081543219876',
            ]
        );

        $desaBodas = Desa::firstOrCreate(
            ['kode_desa' => '3303132008'],
            [
                'user_id' => $userBodas->id,
                'nama_desa' => 'Desa Bodas',
                'kecamatan' => 'Karangjambu',
                'jumlah_kk' => 400,
                'jumlah_jiwa' => 1500,
                'jumlah_balita' => 45,
                'jumlah_lansia' => 30,
                'jumlah_disabilitas' => 5,
                'nama_pj' => 'Slamet Riyadi',
                'no_wa' => '081543219876',
                'lokasi_drop_point' => 'Depan Balai Pertemuan RW 02',
                'latitude' => -7.2201000,
                'longitude' => 109.4102000,
            ]
        );

        $simulasiBodas = SimulasiKelayakan::create([
            'desa_id' => $desaBodas->id,
            'kk_terdampak' => 20,
            'jiwa_rentan' => 5,
            'hari_tanpa_air' => 2,
            'jarak_sumber_air' => 200,
            'skor_ahp_saw' => 48.10,
            'status_kelayakan' => 'Aman',
            'rekomendasi' => 'Kondisi pasokan air masih terkendali secara mandiri di tingkat lingkungan.',
        ]);
    }
}
