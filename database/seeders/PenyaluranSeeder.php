<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Penyaluran;
use App\Models\Penyalur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenyaluranSeeder extends Seeder
{
    /**
     * Run the database seeds for F-08: Laporan Penyaluran
     */
    public function run(): void
    {
        Penyaluran::ensureTableExists();

        // 1. Akun Mitra Penyalur 1: PMI Kabupaten Purbalingga
        $userPmi = User::firstOrCreate(
            ['username' => 'penyalur_pmi'],
            [
                'password' => Hash::make('password123'),
                'role' => 'penyalur',
                'phone' => '081227891100',
            ]
        );
        $penyalurPmi = Penyalur::updateOrCreate(
            ['user_id' => $userPmi->id],
            [
                'nama_penyalur' => 'PMI Cabang Purbalingga',
                'kapasitas_tangki_liter' => 5000,
                'no_telepon' => '0281-891044',
                'status' => 'aktif',
            ]
        );

        // 2. Akun Mitra Penyalur 2: PDAM Tirta Perwira
        $userPdam = User::firstOrCreate(
            ['username' => 'penyalur_pdam'],
            [
                'password' => Hash::make('password123'),
                'role' => 'penyalur',
                'phone' => '081391234567',
            ]
        );
        $penyalurPdam = Penyalur::updateOrCreate(
            ['user_id' => $userPdam->id],
            [
                'nama_penyalur' => 'Perumda Air Minum Tirta Perwira',
                'kapasitas_tangki_liter' => 5000,
                'no_telepon' => '0281-891122',
                'status' => 'aktif',
            ]
        );

        // 3. Akun Mitra Penyalur 3: Unit Armada Tangki BPBD
        $userBpbdArmada = User::firstOrCreate(
            ['username' => 'penyalur_bpbd'],
            [
                'password' => Hash::make('password123'),
                'role' => 'penyalur',
                'phone' => '082133445566',
            ]
        );
        $penyalurBpbd = Penyalur::updateOrCreate(
            ['user_id' => $userBpbdArmada->id],
            [
                'nama_penyalur' => 'Armada Tangki Reaksi Cepat BPBD',
                'kapasitas_tangki_liter' => 5000,
                'no_telepon' => '0281-891011',
                'status' => 'aktif',
            ]
        );

        // Cari atau pastikan referensi desa tersedia
        $desaSerang = Desa::where('nama_desa', 'like', '%Serang%')->first();
        $desaKutabawa = Desa::where('nama_desa', 'like', '%Kutabawa%')->first();
        $desaSelakambang = Desa::where('nama_desa', 'like', '%Selakambang%')->first();
        $desaKarangcegak = Desa::where('nama_desa', 'like', '%Karangcegak%')->first();

        $kodeSerang = $desaSerang ? $desaSerang->kode_desa : '33.03.11.2005';
        $kodeKutabawa = $desaKutabawa ? $desaKutabawa->kode_desa : '33.03.11.2004';
        $kodeSelakambang = $desaSelakambang ? $desaSelakambang->kode_desa : '33.03.04.2008';
        $kodeKarangcegak = $desaKarangcegak ? $desaKarangcegak->kode_desa : '33.03.05.2003';

        // 4. Data Rekap Penyaluran Realistis (PBI-022 & PBI-023)
        $dataPenyaluran = [
            // --- Penyaluran Milik PMI ---
            [
                'kode_penyaluran' => 'DIST-202611-001',
                'nomor_tiket' => 'TKT-202611-001',
                'kode_desa' => $kodeSerang,
                'penyalur_id' => $penyalurPmi->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(6)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Bak Penampungan Sentral RT 04 RW 02',
                'nama_penerima' => 'Bambang Sugeng (Kades)',
                'no_telepon_penerima' => '081234567891',
                'armada_nopol' => 'R 9501 C',
                'nama_driver' => 'Joko Susilo',
                'catatan' => 'Penyaluran tahap 1 tangki 5.000 liter diterima warga dalam keadaan baik dan tertib.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186c5f7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-002',
                'nomor_tiket' => 'TKT-202611-001',
                'kode_desa' => $kodeSerang,
                'penyalur_id' => $penyalurPmi->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(4)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Tandon Darurat Dusun Bambu RT 02',
                'nama_penerima' => 'Agus Santoso (Kadus)',
                'no_telepon_penerima' => '081234567892',
                'armada_nopol' => 'R 9501 C',
                'nama_driver' => 'Joko Susilo',
                'catatan' => 'Penyaluran pasokan air bersih tahap 2 berjalan lancar.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1578328819058-b69f3a3b0f6b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-003',
                'nomor_tiket' => 'TKT-202611-003',
                'kode_desa' => $kodeSelakambang,
                'penyalur_id' => $penyalurPmi->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(2)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Tandon Air Bersama RW 03',
                'nama_penerima' => 'Rahmat Hidayat (Kadus 2)',
                'no_telepon_penerima' => '085712345678',
                'armada_nopol' => 'R 9502 C',
                'nama_driver' => 'Surono',
                'catatan' => 'Pengisian bak penampungan warga RW 03 selesai pukul 14.30 WIB.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1544984243-ec57ea16fe25?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-004',
                'nomor_tiket' => 'TKT-202611-004',
                'kode_desa' => $kodeKarangcegak,
                'penyalur_id' => $penyalurPmi->id,
                'tanggal_penyaluran' => Carbon::now()->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Dalam Pengiriman',
                'titik_serah_terima' => 'Kompleks Masjid Al-Ikhlas RW 01',
                'nama_penerima' => 'Triyono (Ketua RW)',
                'no_telepon_penerima' => '087812349988',
                'armada_nopol' => 'R 9501 C',
                'nama_driver' => 'Joko Susilo',
                'catatan' => 'Armada tangki dalam perjalanan menuju lokasi distribusi.',
                'foto_bukti' => null,
            ],

            // --- Penyaluran Milik PDAM Tirta Perwira ---
            [
                'kode_penyaluran' => 'DIST-202611-005',
                'nomor_tiket' => 'TKT-202611-002',
                'kode_desa' => $kodeKutabawa,
                'penyalur_id' => $penyalurPdam->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(5)->toDateString(),
                'volume_air_liter' => 10000,
                'jumlah_tangki' => 2,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Halaman Balai Desa Kutabawa',
                'nama_penerima' => 'Siti Nurhaliza (Kaur Kesra)',
                'no_telepon_penerima' => '082198765432',
                'armada_nopol' => 'R 8024 ZA',
                'nama_driver' => 'Wahyu Hidayat',
                'catatan' => 'Penyaluran 2 unit tangki PDAM 10.000 liter untuk 140 KK terdampak.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-006',
                'nomor_tiket' => 'TKT-202611-002',
                'kode_desa' => $kodeKutabawa,
                'penyalur_id' => $penyalurPdam->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(3)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Tandon Dusun 3 Kutabawa',
                'nama_penerima' => 'Edi Wibowo',
                'no_telepon_penerima' => '082198765433',
                'armada_nopol' => 'R 8025 ZA',
                'nama_driver' => 'Budi Prasetyo',
                'catatan' => 'Penyaluran tambahan air bersih 5.000 liter selesai tervalidasi.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1541888946425-d0fbb186c5f7?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-007',
                'nomor_tiket' => 'TKT-202611-003',
                'kode_desa' => $kodeSelakambang,
                'penyalur_id' => $penyalurPdam->id,
                'tanggal_penyaluran' => Carbon::now()->addDay()->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Dijadwalkan',
                'titik_serah_terima' => 'Pos Ronda RW 04 Selakambang',
                'nama_penerima' => 'Rahmat Hidayat',
                'no_telepon_penerima' => '085712345678',
                'armada_nopol' => 'R 8024 ZA',
                'nama_driver' => 'Wahyu Hidayat',
                'catatan' => 'Jadwal pengiriman pukul 09.00 WIB pagi.',
                'foto_bukti' => null,
            ],

            // --- Penyaluran Milik BPBD Armada Tangki ---
            [
                'kode_penyaluran' => 'DIST-202611-008',
                'nomor_tiket' => 'TKT-202611-001',
                'kode_desa' => $kodeSerang,
                'penyalur_id' => $penyalurBpbd->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(5)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Bak Penampungan Sentral RT 04 RW 02',
                'nama_penerima' => 'Bambang Sugeng',
                'no_telepon_penerima' => '081234567891',
                'armada_nopol' => 'R 9912 B',
                'nama_driver' => 'Slamet Riyadi',
                'catatan' => 'Armada TRC BPBD drop air 5.000 liter lancar.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1578328819058-b69f3a3b0f6b?auto=format&fit=crop&w=600&q=80',
            ],
            [
                'kode_penyaluran' => 'DIST-202611-009',
                'nomor_tiket' => 'TKT-202611-002',
                'kode_desa' => $kodeKutabawa,
                'penyalur_id' => $penyalurBpbd->id,
                'tanggal_penyaluran' => Carbon::now()->subDays(1)->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Kendala',
                'titik_serah_terima' => 'Dusun 4 Tanjakan Cilik',
                'nama_penerima' => 'Siti Nurhaliza',
                'no_telepon_penerima' => '082198765432',
                'armada_nopol' => 'R 9912 B',
                'nama_driver' => 'Slamet Riyadi',
                'catatan' => 'Tanjakan licin akibat gerimis, distribusi dialihkan sementara ke titik kumpul balai desa.',
                'foto_bukti' => null,
            ],
            [
                'kode_penyaluran' => 'DIST-202611-010',
                'nomor_tiket' => 'TKT-202611-004',
                'kode_desa' => $kodeKarangcegak,
                'penyalur_id' => $penyalurBpbd->id,
                'tanggal_penyaluran' => Carbon::now()->toDateString(),
                'volume_air_liter' => 5000,
                'jumlah_tangki' => 1,
                'status' => 'Terkirim',
                'titik_serah_terima' => 'Kompleks Masjid Al-Ikhlas RW 01',
                'nama_penerima' => 'Triyono (Ketua RW)',
                'no_telepon_penerima' => '087812349988',
                'armada_nopol' => 'R 9913 B',
                'nama_driver' => 'Haryanto',
                'catatan' => 'Distribusi air tangki TRC BPBD selesai dan tervalidasi warga.',
                'foto_bukti' => 'https://images.unsplash.com/photo-1544984243-ec57ea16fe25?auto=format&fit=crop&w=600&q=80',
            ],
        ];

        foreach ($dataPenyaluran as $item) {
            Penyaluran::updateOrCreate(
                ['kode_penyaluran' => $item['kode_penyaluran']],
                $item
            );
        }
    }
}
