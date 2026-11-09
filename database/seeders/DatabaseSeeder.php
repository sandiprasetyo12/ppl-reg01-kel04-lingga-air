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
        // 1. Akun Admin BPBD Purbalingga
        User::firstOrCreate(
            ['email' => 'admin@bpbd.purbalinggakab.go.id'],
            [
                'name' => 'Petugas Verifikator BPBD',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Desa Pemohon (Data Acuan Desa sesuai Fitur 1 & 2)
        $desa1 = Desa::firstOrCreate(
            ['nama_desa' => 'Serang', 'kecamatan' => 'Karangreja'],
            [
                'penanggung_jawab' => 'Bambang Sugeng (Kepala Desa)',
                'kontak_wa' => '081234567891',
                'jumlah_kk' => 650,
                'jumlah_jiwa' => 2400,
                'jumlah_balita' => 45,
                'jumlah_lansia' => 50,
                'jumlah_disabilitas' => 25,
                'titik_drop_air' => 'Bak Penampungan Sentral RT 04 RW 02',
            ]
        );

        $desa2 = Desa::firstOrCreate(
            ['nama_desa' => 'Kutabawa', 'kecamatan' => 'Karangreja'],
            [
                'penanggung_jawab' => 'Siti Nurhaliza (Kaur Kesra)',
                'kontak_wa' => '082198765432',
                'jumlah_kk' => 520,
                'jumlah_jiwa' => 1950,
                'jumlah_balita' => 35,
                'jumlah_lansia' => 35,
                'jumlah_disabilitas' => 15,
                'titik_drop_air' => 'Halaman Balai Desa Kutabawa',
            ]
        );

        $desa3 = Desa::firstOrCreate(
            ['nama_desa' => 'Selakambang', 'kecamatan' => 'Kaligondang'],
            [
                'penanggung_jawab' => 'Rahmat Hidayat (Kadus 2)',
                'kontak_wa' => '085712345678',
                'jumlah_kk' => 430,
                'jumlah_jiwa' => 1600,
                'jumlah_balita' => 25,
                'jumlah_lansia' => 25,
                'jumlah_disabilitas' => 10,
                'titik_drop_air' => 'Tandon Air Bersama RW 03',
            ]
        );

        $desa4 = Desa::firstOrCreate(
            ['nama_desa' => 'Karangcegak', 'kecamatan' => 'Kutasari'],
            [
                'penanggung_jawab' => 'Triyono (Ketua RW 01)',
                'kontak_wa' => '087812349988',
                'jumlah_kk' => 380,
                'jumlah_jiwa' => 1350,
                'jumlah_balita' => 20,
                'jumlah_lansia' => 18,
                'jumlah_disabilitas' => 7,
                'titik_drop_air' => 'Kompleks Masjid Al-Ikhlas RW 01',
            ]
        );

        $desa5 = Desa::firstOrCreate(
            ['nama_desa' => 'Tanalum', 'kecamatan' => 'Rembang'],
            [
                'penanggung_jawab' => 'Wahyudi (Sekretaris Desa)',
                'kontak_wa' => '089611223344',
                'jumlah_kk' => 310,
                'jumlah_jiwa' => 1100,
                'jumlah_balita' => 12,
                'jumlah_lansia' => 12,
                'jumlah_disabilitas' => 6,
                'titik_drop_air' => 'Bak Distribusi Air RT 02 RW 01',
            ]
        );

        // 3. Data Permohonan (Sinkron dengan Ambang Batas F-02):
        // 70.00 - 100.00 : KRITIS
        // 40.00 - 69.99  : WASPADA
        // 0.00  - 39.99  : AMAN

        // Peringkat 1: KRITIS (Skor 94.50)
        Permohonan::updateOrCreate(
            ['nomor_tiket' => 'TKT-202611-001'],
            [
                'desa_id' => $desa1->id,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 180,
                'jiwa_rentan' => 75,
                'hari_tanpa_air' => 7,         // Maksimal normalisasi 7 hari
                'jarak_sumber_air' => 2000,    // Maksimal normalisasi 2000 meter
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
                'desa_id' => $desa2->id,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 140,
                'jiwa_rentan' => 50,
                'hari_tanpa_air' => 6,
                'jarak_sumber_air' => 1750,    // 1.750 meter
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
                'desa_id' => $desa3->id,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 80,
                'jiwa_rentan' => 28,
                'hari_tanpa_air' => 4,
                'jarak_sumber_air' => 1300,    // 1.300 meter
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
                'desa_id' => $desa4->id,
                'status' => 'Menunggu Verifikasi',
                'kk_terdampak' => 55,
                'jiwa_rentan' => 16,
                'hari_tanpa_air' => 3,
                'jarak_sumber_air' => 900,     // 900 meter
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

        // Catatan Konsistensi Aturan F-02 & F-04:
        // Desa Tanalum (skor simulasi 28.40) berkategori AMAN (< 40.00). Sesuai aturan PBI-005 AC 2:
        // Status AMAN menonaktifkan akses pengajuan sehingga desa TIDAK DAPAT melanjutkan ke tahap F-03.
        // Dengan demikian, antrean verifikasi BPBD (F-04) secara konsisten HANYA memproses permohonan yang lolos skrining (KRITIS & WASPADA).
        Permohonan::where('nomor_tiket', 'TKT-202611-005')->delete();
    }
}
