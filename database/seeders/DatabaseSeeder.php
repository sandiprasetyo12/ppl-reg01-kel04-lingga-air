<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Desa;
use App\Models\Permohonan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil seeder master 239 desa Purbalingga (F-01)
        $this->call([DesaSeeder::class]);

        // 2. Panggil seeder akun admin BPBD (F-01)
        $this->call([BpbdAdminSeeder::class]);

        // 3. Akun perwakilan Desa Serang (F-01/F-03 untuk simulasi login pihak desa)
        $userSerang = User::firstOrCreate(
            ['username' => 'desa_serang'],
            [
                'password' => Hash::make('password123'),
                'role' => 'desa',
                'phone' => '081398765432',
            ]
        );

        // 4. Lengkapi Data Acuan Lapangan untuk 4 Desa Simulasi Bencana (F-01, F-02, F-04)
        $desa1 = Desa::where('nama_desa', 'like', '%Serang%')->first() ?? Desa::firstOrCreate(
            ['kode_desa' => '33.03.11.2005'],
            ['nama_desa' => 'Serang', 'kecamatan' => 'Karangreja']
        );
        $desa1->update([
            'user_id' => $userSerang->id,
            'nama_pj_darurat' => 'Bambang Sugeng (Kepala Desa)',
            'no_wa_darurat' => '081234567891',
            'jumlah_kk_total' => 650,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 45,
            'jumlah_lansia' => 50,
            'jumlah_disabilitas' => 25,
            'titik_drop_air' => 'Bak Penampungan Sentral RT 04 RW 02',
        ]);

        $desa2 = Desa::where('nama_desa', 'like', '%Kutabawa%')->first() ?? Desa::firstOrCreate(
            ['kode_desa' => '33.03.11.2004'],
            ['nama_desa' => 'Kutabawa', 'kecamatan' => 'Karangreja']
        );
        $desa2->update([
            'nama_pj_darurat' => 'Siti Nurhaliza (Kaur Kesra)',
            'no_wa_darurat' => '082198765432',
            'jumlah_kk_total' => 520,
            'jumlah_jiwa_total' => 1950,
            'jumlah_balita' => 35,
            'jumlah_lansia' => 35,
            'jumlah_disabilitas' => 15,
            'titik_drop_air' => 'Halaman Balai Desa Kutabawa',
        ]);

        $desa3 = Desa::where('nama_desa', 'like', '%Selakambang%')->first() ?? Desa::firstOrCreate(
            ['kode_desa' => '33.03.04.2008'],
            ['nama_desa' => 'Selakambang', 'kecamatan' => 'Kaligondang']
        );
        $desa3->update([
            'nama_pj_darurat' => 'Rahmat Hidayat (Kadus 2)',
            'no_wa_darurat' => '085712345678',
            'jumlah_kk_total' => 430,
            'jumlah_jiwa_total' => 1600,
            'jumlah_balita' => 25,
            'jumlah_lansia' => 25,
            'jumlah_disabilitas' => 10,
            'titik_drop_air' => 'Tandon Air Bersama RW 03',
        ]);

        $desa4 = Desa::where('nama_desa', 'like', '%Karangcegak%')->first() ?? Desa::firstOrCreate(
            ['kode_desa' => '33.03.05.2003'],
            ['nama_desa' => 'Karangcegak', 'kecamatan' => 'Kutasari']
        );
        $desa4->update([
            'nama_pj_darurat' => 'Triyono (Ketua RW 01)',
            'no_wa_darurat' => '087812349988',
            'jumlah_kk_total' => 380,
            'jumlah_jiwa_total' => 1350,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 18,
            'jumlah_disabilitas' => 7,
            'titik_drop_air' => 'Kompleks Masjid Al-Ikhlas RW 01',
        ]);

        // 5. Data Permohonan Antrean Verifikasi Fitur 4 (Hanya yang lolos skrining: KRITIS & WASPADA)
        // Peringkat 1: KRITIS (Skor 94.50)
        Permohonan::updateOrCreate(
            ['nomor_tiket' => 'TKT-202611-001'],
            [
                'kode_desa' => $desa1->kode_desa,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 180,
                'jiwa_rentan' => 75,
                'hari_tanpa_air' => 7,
                'jarak_sumber_air' => 2000,
                'kebutuhan_tangki' => 4,
                'alasan_permohonan' => 'Mata air lereng bukit mengering total. Kondisi sangat darurat, warga terpaksa membeli air galon mahal.',
                'skor_ahp_saw' => 94.50,
                'status_kelayakan' => 'Kritis',
                'rincian_kriteria' => [
                    'c1_rasio_kk' => 95.0,
                    'c2_proporsi_rentan' => 92.0,
                    'c3_hari_tanpa_air' => 100.0,
                    'c4_jarak_sumber' => 100.0,
                ],
                'surat_kades' => 'surat_kades_serang.pdf',
                'foto_lapangan' => 'https://images.unsplash.com/photo-1578328819058-b69f3a3b0f6b?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subHours(8),
            ]
        );

        // Peringkat 2: KRITIS (Skor 82.30)
        Permohonan::updateOrCreate(
            ['nomor_tiket' => 'TKT-202611-002'],
            [
                'kode_desa' => $desa2->kode_desa,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 140,
                'jiwa_rentan' => 50,
                'hari_tanpa_air' => 6,
                'jarak_sumber_air' => 1750,
                'kebutuhan_tangki' => 3,
                'alasan_permohonan' => 'Debit penampungan umum desa habis, sumur galian warga mengering sejak seminggu lalu.',
                'skor_ahp_saw' => 82.30,
                'status_kelayakan' => 'Kritis',
                'rincian_kriteria' => [
                    'c1_rasio_kk' => 82.0,
                    'c2_proporsi_rentan' => 80.0,
                    'c3_hari_tanpa_air' => 85.7,
                    'c4_jarak_sumber' => 87.5,
                ],
                'surat_kades' => 'surat_kades_kutabawa.pdf',
                'foto_lapangan' => 'https://images.unsplash.com/photo-1544984243-ec57ea16fe25?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subHours(5),
            ]
        );

        // Peringkat 3: WASPADA (Skor 62.80)
        Permohonan::updateOrCreate(
            ['nomor_tiket' => 'TKT-202611-003'],
            [
                'kode_desa' => $desa3->kode_desa,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 80,
                'jiwa_rentan' => 28,
                'hari_tanpa_air' => 4,
                'jarak_sumber_air' => 1300,
                'kebutuhan_tangki' => 2,
                'alasan_permohonan' => 'Sumur warga mulai keruh dan surut, pasokan mulai berkurang untuk 2 RW.',
                'skor_ahp_saw' => 62.80,
                'status_kelayakan' => 'Waspada',
                'rincian_kriteria' => [
                    'c1_rasio_kk' => 65.0,
                    'c2_proporsi_rentan' => 60.0,
                    'c3_hari_tanpa_air' => 57.1,
                    'c4_jarak_sumber' => 65.0,
                ],
                'surat_kades' => 'surat_kades_selakambang.pdf',
                'foto_lapangan' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186c5f7?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subHours(3),
            ]
        );

        // Peringkat 4: WASPADA (Skor 48.50)
        Permohonan::updateOrCreate(
            ['nomor_tiket' => 'TKT-202611-004'],
            [
                'kode_desa' => $desa4->kode_desa,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 55,
                'jiwa_rentan' => 16,
                'hari_tanpa_air' => 3,
                'jarak_sumber_air' => 900,
                'kebutuhan_tangki' => 1,
                'alasan_permohonan' => 'Aliran air mengecil pada siang hari, cadangan air warga bertahan 2 hari lagi.',
                'skor_ahp_saw' => 48.50,
                'status_kelayakan' => 'Waspada',
                'rincian_kriteria' => [
                    'c1_rasio_kk' => 50.0,
                    'c2_proporsi_rentan' => 46.0,
                    'c3_hari_tanpa_air' => 42.8,
                    'c4_jarak_sumber' => 45.0,
                ],
                'surat_kades' => 'surat_kades_karangcegak.pdf',
                'foto_lapangan' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subHours(2),
            ]
        );
    }
}