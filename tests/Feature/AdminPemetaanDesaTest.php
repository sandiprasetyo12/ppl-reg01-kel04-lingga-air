<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\Penyalur;
use App\Models\Permohonan;
use App\Models\RiwayatCekKelayakan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPemetaanDesaTest extends TestCase
{
    use RefreshDatabase;

    private function createBpbdUser(string $username = 'bpbd_gis_test'): User
    {
        return User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'bpbd',
            'phone' => '081234567890',
        ]);
    }

    private function createOnboardedDesaUser(): array
    {
        $user = User::create([
            'username' => 'desa_gis_test',
            'password' => 'password123',
            'role' => 'desa',
            'phone' => '081234567891',
        ]);

        $desa = Desa::create([
            'kode_desa' => '33.03.99.2001',
            'user_id' => $user->id,
            'nama_desa' => 'Desa Uji Akses',
            'kecamatan' => 'Karangreja',
            'jumlah_kk_total' => 300,
            'jumlah_jiwa_total' => 1100,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
            'latitude' => -7.350000,
            'longitude' => 109.350000,
        ]);

        return [$user, $desa];
    }

    private function createOnboardedPenyalurUser(): User
    {
        $user = User::create([
            'username' => 'penyalur_gis_test',
            'password' => 'password123',
            'role' => 'penyalur',
            'phone' => '081234567892',
        ]);

        Penyalur::create([
            'user_id' => $user->id,
            'nama_penyalur' => 'Armada Tangki Uji',
            'kapasitas_tangki_liter' => 5000,
            'no_telepon' => '081234567892',
            'status' => 'aktif',
        ]);

        return $user;
    }

    public function test_only_authenticated_bpbd_admin_can_access_pemetaan_endpoints(): void
    {
        [$desaUser, $desa] = $this->createOnboardedDesaUser();
        $penyalurUser = $this->createOnboardedPenyalurUser();
        $bpbdUser = $this->createBpbdUser();

        // Guest ditolak
        $this->get(route('admin.pemetaan.index'))->assertRedirect(route('login'));
        $this->getJson(route('admin.pemetaan.data'))->assertUnauthorized();
        $this->getJson(route('admin.pemetaan.show', $desa->kode_desa))->assertUnauthorized();
        $this->patchJson(route('admin.pemetaan.koordinat', $desa->kode_desa), [
            'latitude' => -7.35,
            'longitude' => 109.35,
        ])->assertUnauthorized();

        // Peran Desa ditolak 403
        $this->actingAs($desaUser)->get(route('admin.pemetaan.index'))->assertForbidden();
        $this->actingAs($desaUser)->getJson(route('admin.pemetaan.data'))->assertForbidden();
        $this->actingAs($desaUser)->getJson(route('admin.pemetaan.show', $desa->kode_desa))->assertForbidden();
        $this->actingAs($desaUser)->patchJson(route('admin.pemetaan.koordinat', $desa->kode_desa), [
            'latitude' => -7.35,
            'longitude' => 109.35,
        ])->assertForbidden();

        // Peran Penyalur ditolak 403
        $this->actingAs($penyalurUser)->get(route('admin.pemetaan.index'))->assertForbidden();
        $this->actingAs($penyalurUser)->getJson(route('admin.pemetaan.data'))->assertForbidden();
        $this->actingAs($penyalurUser)->getJson(route('admin.pemetaan.show', $desa->kode_desa))->assertForbidden();
        $this->actingAs($penyalurUser)->patchJson(route('admin.pemetaan.koordinat', $desa->kode_desa), [
            'latitude' => -7.35,
            'longitude' => 109.35,
        ])->assertForbidden();

        // Admin BPBD diizinkan
        $response = $this->actingAs($bpbdUser)->get(route('admin.pemetaan.index'));
        $response->assertOk();
        $response->assertSee('Pemetaan Sebaran Desa (GIS)', false);
        $response->assertSee('Karangreja');
    }

    public function test_map_data_hides_desas_without_valid_coordinates(): void
    {
        $bpbd = $this->createBpbdUser();

        Desa::create([
            'kode_desa' => '33.03.01.2001',
            'nama_desa' => 'Desa Valid Peta',
            'kecamatan' => 'Kemangkon',
            'latitude' => -7.450000,
            'longitude' => 109.350000,
        ]);
        Desa::create([
            'kode_desa' => '33.03.01.2002',
            'nama_desa' => 'Desa Null Lat',
            'kecamatan' => 'Kemangkon',
            'latitude' => null,
            'longitude' => 109.360000,
        ]);
        Desa::create([
            'kode_desa' => '33.03.01.2003',
            'nama_desa' => 'Desa Nol Lat',
            'kecamatan' => 'Kemangkon',
            'latitude' => 0,
            'longitude' => 109.370000,
        ]);
        Desa::create([
            'kode_desa' => '33.03.01.2004',
            'nama_desa' => 'Desa Nol Lng',
            'kecamatan' => 'Kemangkon',
            'latitude' => -7.460000,
            'longitude' => 0,
        ]);

        $response = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));

        $response->assertOk()
            ->assertJsonPath('summary.total_terpetakan', 1)
            ->assertJsonPath('summary.ditampilkan', 1)
            ->assertJsonPath('summary.tanpa_koordinat', 3)
            ->assertJsonCount(1, 'markers')
            ->assertJsonCount(3, 'unmapped');

        $markerCodes = collect($response->json('markers'))->pluck('kode_desa')->all();
        $unmappedCodes = collect($response->json('unmapped'))->pluck('kode_desa')->all();

        $this->assertSame(['33.03.01.2001'], $markerCodes);
        $this->assertEqualsCanonicalizing(['33.03.01.2002', '33.03.01.2003', '33.03.01.2004'], $unmappedCodes);
    }

    public function test_map_data_colors_markers_based_on_ticket_progress(): void
    {
        $bpbd = $this->createBpbdUser();

        $makeDesa = fn (string $kode, string $nama) => Desa::create([
            'kode_desa' => $kode,
            'nama_desa' => $nama,
            'kecamatan' => 'Kutasari',
            'latitude' => -7.380000,
            'longitude' => 109.330000,
        ]);

        // 1. Tanpa pengajuan -> Abu-abu
        $makeDesa('33.03.07.0001', 'Desa Kosong');

        // 2. Pengajuan Draf -> Abu-abu
        $dDraf = $makeDesa('33.03.07.0002', 'Desa Draf');
        Pengajuan::create([
            'kode_desa' => $dDraf->kode_desa,
            'status' => 'Draf',
            'jumlah_kk' => 40,
            'hari_tanpa_air' => 3,
            'jarak_sumber_air' => 800,
            'alasan_pengajuan' => 'Masih draf',
        ]);

        // 3. Ditolak -> Abu-abu
        $dDitolak = $makeDesa('33.03.07.0003', 'Desa Ditolak');
        Pengajuan::create([
            'nomor_tiket' => 'LA-20261111-003',
            'kode_desa' => $dDitolak->kode_desa,
            'status' => 'Ditolak',
            'jumlah_kk' => 30,
            'hari_tanpa_air' => 2,
            'jarak_sumber_air' => 600,
            'alasan_pengajuan' => 'Ditolak verifikator',
        ]);

        // 4. Diajukan, Dalam Telaah, Revisi, dan Menunggu Verifikasi -> Merah
        foreach ([
            ['33.03.07.0004', 'Desa Diajukan', 'Diajukan'],
            ['33.03.07.0005', 'Desa Telaah', 'Dalam Telaah'],
            ['33.03.07.0006', 'Desa Revisi', 'Revisi'],
        ] as $idx => [$kode, $nama, $status]) {
            $d = $makeDesa($kode, $nama);
            Pengajuan::create([
                'nomor_tiket' => "LA-20261111-10{$idx}",
                'kode_desa' => $d->kode_desa,
                'status' => $status,
                'jumlah_kk' => 60,
                'hari_tanpa_air' => 4,
                'jarak_sumber_air' => 1100,
                'alasan_pengajuan' => 'Darurat air bersih',
                'tanggal_pengajuan' => now(),
            ]);
        }

        $dPermohonanOnly = $makeDesa('33.03.07.0007', 'Desa Permohonan Only');
        Permohonan::create([
            'nomor_tiket' => 'TKT-ONLY-001',
            'kode_desa' => $dPermohonanOnly->kode_desa,
            'status' => 'Menunggu Verifikasi',
            'kk_terdampak' => 75,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 1200,
            'kebutuhan_tangki' => 2,
            'skor_ahp_saw' => 64.00,
            'status_kelayakan' => 'Kritis',
        ]);

        // 5. Disetujui & Dijadwalkan -> Kuning
        foreach ([
            ['33.03.07.0008', 'Desa Disetujui', 'Disetujui'],
            ['33.03.07.0009', 'Desa Dijadwalkan', 'Dijadwalkan'],
        ] as $idx => [$kode, $nama, $status]) {
            $d = $makeDesa($kode, $nama);
            Pengajuan::create([
                'nomor_tiket' => "LA-20261111-20{$idx}",
                'kode_desa' => $d->kode_desa,
                'status' => $status,
                'jumlah_kk' => 90,
                'hari_tanpa_air' => 5,
                'jarak_sumber_air' => 1500,
                'alasan_pengajuan' => 'Siap kirim',
                'tanggal_pengajuan' => now(),
            ]);
        }

        // 6. Selesai -> Hijau
        $dSelesai = $makeDesa('33.03.07.0010', 'Desa Selesai');
        Pengajuan::create([
            'nomor_tiket' => 'LA-20261111-300',
            'kode_desa' => $dSelesai->kode_desa,
            'status' => 'Selesai',
            'jumlah_kk' => 100,
            'hari_tanpa_air' => 6,
            'jarak_sumber_air' => 1400,
            'alasan_pengajuan' => 'Sudah tersalurkan',
            'tanggal_pengajuan' => now(),
        ]);

        $response = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));

        $response->assertOk()
            ->assertJsonPath('summary.merah', 4)
            ->assertJsonPath('summary.kuning', 2)
            ->assertJsonPath('summary.hijau', 1)
            ->assertJsonPath('summary.abu_abu', 3)
            ->assertJsonPath('summary.total_terpetakan', 10);

        $byCode = collect($response->json('markers'))->keyBy('kode_desa');
        $this->assertSame('#6B7280', $byCode['33.03.07.0001']['status_gis']['kode_warna']);
        $this->assertSame('#6B7280', $byCode['33.03.07.0002']['status_gis']['kode_warna']);
        $this->assertSame('#6B7280', $byCode['33.03.07.0003']['status_gis']['kode_warna']);
        $this->assertSame('#DC2626', $byCode['33.03.07.0004']['status_gis']['kode_warna']);
        $this->assertSame('#DC2626', $byCode['33.03.07.0006']['status_gis']['kode_warna']);
        $this->assertSame('#DC2626', $byCode['33.03.07.0007']['status_gis']['kode_warna']);
        $this->assertSame('#D97706', $byCode['33.03.07.0008']['status_gis']['kode_warna']);
        $this->assertSame('#D97706', $byCode['33.03.07.0009']['status_gis']['kode_warna']);
        $this->assertSame('#059669', $byCode['33.03.07.0010']['status_gis']['kode_warna']);
    }

    public function test_kecamatan_status_and_search_filters_work_correctly(): void
    {
        $bpbd = $this->createBpbdUser();

        $serang = Desa::create([
            'kode_desa' => '33.03.10.2001',
            'nama_desa' => 'Serang',
            'kecamatan' => 'Karangreja',
            'latitude' => -7.235,
            'longitude' => 109.290,
        ]);
        Pengajuan::create([
            'nomor_tiket' => 'LA-20261111-401',
            'kode_desa' => $serang->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Kekeringan lereng Slamet',
            'tanggal_pengajuan' => now(),
        ]);

        Desa::create([
            'kode_desa' => '33.03.10.2002',
            'nama_desa' => 'Kutabawa',
            'kecamatan' => 'Karangreja',
            'latitude' => -7.245,
            'longitude' => 109.275,
        ]);

        Desa::create([
            'kode_desa' => '33.03.04.2006',
            'nama_desa' => 'Selakambang',
            'kecamatan' => 'Kaligondang',
            'latitude' => -7.365,
            'longitude' => 109.420,
        ]);

        // Filter kecamatan saja
        $respKec = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data', ['kecamatan' => 'Karangreja']));
        $respKec->assertOk()
            ->assertJsonPath('summary.total_terpetakan', 2)
            ->assertJsonPath('summary.merah', 1)
            ->assertJsonPath('summary.abu_abu', 1)
            ->assertJsonCount(2, 'markers');

        // Filter kecamatan + status merah (ringkasan kecamatan tetap terlihat, marker tersaring)
        $respFilter = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data', [
            'kecamatan' => 'Karangreja',
            'status' => 'merah',
        ]));
        $respFilter->assertOk()
            ->assertJsonPath('summary.total_terpetakan', 2)
            ->assertJsonPath('summary.ditampilkan', 1)
            ->assertJsonCount(1, 'markers')
            ->assertJsonPath('markers.0.kode_desa', '33.03.10.2001');

        // Filter search berdasarkan nama desa
        $respSearch = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data', ['search' => 'Selakambang']));
        $respSearch->assertOk()
            ->assertJsonCount(1, 'markers')
            ->assertJsonPath('markers.0.nama_desa', 'Selakambang');
    }

    public function test_show_detail_endpoint_returns_kelayakan_30_60_and_5000l_tank_estimation(): void
    {
        $bpbd = $this->createBpbdUser();

        // Kasus A: Membaca skor dari riwayat_cek_kelayakan + pengajuan (55 KK -> 5.500 L -> 2 tangki)
        $karangcegak = Desa::create([
            'kode_desa' => '33.03.07.2012',
            'nama_desa' => 'Karangcegak',
            'kecamatan' => 'Kutasari',
            'latitude' => -7.355,
            'longitude' => 109.335,
            'titik_drop_air' => 'Kompleks Masjid Al-Ikhlas',
            'nama_pj_darurat' => 'Triyono',
            'no_wa_darurat' => '087812349988',
            'jumlah_kk_total' => 380,
            'jumlah_jiwa_total' => 1350,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 18,
            'jumlah_disabilitas' => 7,
        ]);

        $riwayat = RiwayatCekKelayakan::create([
            'kode_desa' => $karangcegak->kode_desa,
            'jumlah_kk_total' => 380,
            'jumlah_jiwa_total' => 1350,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 18,
            'jumlah_disabilitas' => 7,
            'kk_terdampak' => 55,
            'hari_tanpa_air' => 3,
            'jarak_sumber_meter' => 900,
            'c1' => 0.1447,
            'c2' => 0.2222,
            'c3' => 0.4285,
            'c4' => 0.4500,
            'skor' => 48.50,
            'status' => 'waspada',
        ]);

        $pengajuan = Pengajuan::create([
            'nomor_tiket' => 'TKT-202611-004',
            'kode_desa' => $karangcegak->kode_desa,
            'riwayat_cek_kelayakan_id' => $riwayat->id,
            'status' => 'Diajukan',
            'jumlah_kk' => 55,
            'hari_tanpa_air' => 3,
            'jarak_sumber_air' => 900,
            'alasan_pengajuan' => 'Debit air berkurang',
            'lokasi_pengantaran' => 'Kompleks Masjid Al-Ikhlas',
            'tanggal_pengajuan' => now(),
        ]);

        $permohonan = Permohonan::create([
            'nomor_tiket' => 'TKT-202611-004',
            'kode_desa' => $karangcegak->kode_desa,
            'status' => 'Menunggu Verifikasi',
            'kk_terdampak' => 55,
            'hari_tanpa_air' => 3,
            'jarak_sumber_air' => 900,
            'kebutuhan_tangki' => 2,
            'skor_ahp_saw' => 48.50,
            'status_kelayakan' => 'Waspada',
        ]);

        $respA = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.show', $karangcegak->kode_desa));
        $respA->assertOk()
            ->assertJsonPath('kode_desa', '33.03.07.2012')
            ->assertJsonPath('status_gis.warna_key', 'merah')
            ->assertJsonPath('kelayakan.status', 'Waspada')
            ->assertJsonPath('kelayakan.warna_key', 'kuning')
            ->assertJsonPath('kelayakan.skor', 48.5)
            ->assertJsonPath('pengajuan_aktif.nomor_tiket', 'TKT-202611-004')
            ->assertJsonPath('pengajuan_aktif.estimasi_liter', 5500)
            ->assertJsonPath('pengajuan_aktif.estimasi_tangki', 2)
            ->assertJsonPath('pengajuan_aktif.permohonan_id', $permohonan->id);

        // Kasus B: Fallback ke Permohonan tanpa RiwayatCekKelayakan (skor 62.80 >= 60 -> Kritis)
        $selakambang = Desa::create([
            'kode_desa' => '33.03.04.2006',
            'nama_desa' => 'Selakambang',
            'kecamatan' => 'Kaligondang',
            'latitude' => -7.365,
            'longitude' => 109.420,
        ]);

        Permohonan::create([
            'nomor_tiket' => 'TKT-202611-003',
            'kode_desa' => $selakambang->kode_desa,
            'status' => 'Menunggu Verifikasi',
            'kk_terdampak' => 80,
            'hari_tanpa_air' => 4,
            'jarak_sumber_air' => 1300,
            'kebutuhan_tangki' => 2,
            'skor_ahp_saw' => 62.80,
            'status_kelayakan' => 'Kritis',
        ]);

        $respB = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.show', $selakambang->kode_desa));
        $respB->assertOk()
            ->assertJsonPath('kelayakan.status', 'Kritis')
            ->assertJsonPath('kelayakan.warna_key', 'merah')
            ->assertJsonPath('kelayakan.sumber', 'permohonan')
            ->assertJsonPath('pengajuan_aktif.estimasi_liter', 8000)
            ->assertJsonPath('pengajuan_aktif.estimasi_tangki', 2);

        // Kasus C: Kode desa tidak ditemukan -> 404
        $this->actingAs($bpbd)->getJson(route('admin.pemetaan.show', 'TIDAK-ADA'))->assertNotFound();
    }

    public function test_bpbd_can_update_coordinates_and_move_unmapped_desa_onto_map(): void
    {
        $bpbd = $this->createBpbdUser();

        $desa = Desa::create([
            'kode_desa' => '33.03.02.2001',
            'nama_desa' => 'Bukateja Uji',
            'kecamatan' => 'Bukateja',
            'latitude' => null,
            'longitude' => null,
            'titik_drop_air' => null,
        ]);

        // Awalnya masuk unmapped
        $before = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));
        $before->assertOk()
            ->assertJsonCount(0, 'markers')
            ->assertJsonCount(1, 'unmapped');

        // Simpan koordinat valid Purbalingga
        $updateResp = $this->actingAs($bpbd)->patchJson(
            route('admin.pemetaan.koordinat', $desa->kode_desa),
            [
                'latitude' => -7.428150,
                'longitude' => 109.423600,
                'titik_drop_air' => 'Lapangan Balai Desa Bukateja',
            ]
        );

        $updateResp->assertOk()
            ->assertJsonPath('data.kode_desa', '33.03.02.2001')
            ->assertJsonPath('data.koordinat_siap', true)
            ->assertJsonPath('data.titik_drop_air', 'Lapangan Balai Desa Bukateja')
            ->assertJsonPath('marker.latitude', -7.42815);

        $desa->refresh();
        $this->assertEqualsWithDelta(-7.428150, (float) $desa->latitude, 0.00001);
        $this->assertEqualsWithDelta(109.423600, (float) $desa->longitude, 0.00001);
        $this->assertSame('Lapangan Balai Desa Bukateja', $desa->titik_drop_air);

        // Sekarang masuk markers dan keluar dari unmapped
        $after = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));
        $after->assertOk()
            ->assertJsonCount(1, 'markers')
            ->assertJsonCount(0, 'unmapped');
    }

    public function test_rejects_invalid_or_out_of_purbalingga_coordinates(): void
    {
        $bpbd = $this->createBpbdUser();

        $desa = Desa::create([
            'kode_desa' => '33.03.05.2001',
            'nama_desa' => 'Kejobong Uji',
            'kecamatan' => 'Kejobong',
            'latitude' => -7.390000,
            'longitude' => 109.450000,
            'titik_drop_air' => 'Titik Awal',
        ]);

        $invalidPayloads = [
            ['latitude' => 0, 'longitude' => 0],
            ['latitude' => -6.200000, 'longitude' => 106.816666],
            ['latitude' => 7.390000, 'longitude' => 109.450000],
            ['latitude' => -7.390000, 'longitude' => 110.100000],
            ['latitude' => 'bukan-angka', 'longitude' => 'bukan-angka'],
            ['latitude' => -7.390000, 'longitude' => 109.450000, 'titik_drop_air' => str_repeat('X', 151)],
        ];

        foreach ($invalidPayloads as $payload) {
            $resp = $this->actingAs($bpbd)->patchJson(
                route('admin.pemetaan.koordinat', $desa->kode_desa),
                $payload
            );
            $resp->assertStatus(422);
        }

        // Koordinat awal di database tidak berubah
        $desa->refresh();
        $this->assertEqualsWithDelta(-7.390000, (float) $desa->latitude, 0.00001);
        $this->assertEqualsWithDelta(109.450000, (float) $desa->longitude, 0.00001);
        $this->assertSame('Titik Awal', $desa->titik_drop_air);
    }

    public function test_seeded_data_and_verifikasi_approval_sync_update_gis_marker_color(): void
    {
        $this->seed(DatabaseSeeder::class);

        $bpbd = User::where('username', 'admin_bpbd')->firstOrFail();

        // Awal: 239 terpetakan, 4 Merah (Serang, Kutabawa, Selakambang, Karangcegak), 235 Abu-abu
        $initial = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));
        $initial->assertOk()
            ->assertJsonPath('summary.total_terpetakan', 239)
            ->assertJsonPath('summary.tanpa_koordinat', 0)
            ->assertJsonPath('summary.merah', 4)
            ->assertJsonPath('summary.kuning', 0)
            ->assertJsonPath('summary.hijau', 0)
            ->assertJsonPath('summary.abu_abu', 235);

        // Cek detail Desa Karangcegak (Waspada 48.50, 5.500 L -> 2 tangki)
        $detail = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.show', '33.03.07.2012'));
        $detail->assertOk()
            ->assertJsonPath('nama_desa', 'Karangcegak')
            ->assertJsonPath('kelayakan.status', 'Waspada')
            ->assertJsonPath('kelayakan.skor', 48.5)
            ->assertJsonPath('pengajuan_aktif.estimasi_liter', 5500)
            ->assertJsonPath('pengajuan_aktif.estimasi_tangki', 2);

        // Admin BPBD menyetujui TKT-202611-004 via VerifikasiController (F-04)
        $permohonan = Permohonan::where('nomor_tiket', 'TKT-202611-004')->firstOrFail();
        $this->actingAs($bpbd)->post(route('verifikasi.keputusan', $permohonan->id), [
            'status_keputusan' => 'Disetujui',
            'volume_air_disetujui' => 10000,
            'catatan_alasan' => 'Disetujui untuk pengiriman dua armada tangki.',
        ])->assertRedirect(route('verifikasi.show', $permohonan->id));

        // Peta F-09 otomatis berubah: 3 Merah dan 1 Kuning (Karangcegak)
        $afterApproval = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.data'));
        $afterApproval->assertOk()
            ->assertJsonPath('summary.merah', 3)
            ->assertJsonPath('summary.kuning', 1);

        $karangcegakDetail = $this->actingAs($bpbd)->getJson(route('admin.pemetaan.show', '33.03.07.2012'));
        $karangcegakDetail->assertOk()
            ->assertJsonPath('status_gis.warna_key', 'kuning')
            ->assertJsonPath('status_gis.kode_warna', '#D97706')
            ->assertJsonPath('pengajuan_aktif.status', 'Disetujui');
    }
}

