<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardChartTest extends TestCase
{
    use RefreshDatabase;

    private function createBpbdUser(string $username = 'bpbd_chart_test'): User
    {
        return User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'bpbd',
            'phone' => '081234567890',
        ]);
    }

    private function createDesaUser(string $username = 'desa_chart_test'): User
    {
        $user = User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'desa',
            'phone' => '081234567891',
        ]);

        Desa::create([
            'kode_desa' => '33.03.99.5555',
            'user_id' => $user->id,
            'nama_desa' => 'Desa Non-BPBD',
            'kecamatan' => 'Kemangkon',
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 5,
            'jumlah_lansia' => 10,
            'jumlah_disabilitas' => 1,
        ]);

        return $user;
    }

    public function test_guest_cannot_access_chart_data(): void
    {
        $response = $this->getJson(route('admin.dashboard.chart-data'));
        $response->assertUnauthorized();
    }

    public function test_non_bpbd_cannot_access_chart_data(): void
    {
        $desaUser = $this->createDesaUser();

        $response = $this->actingAs($desaUser)->getJson(route('admin.dashboard.chart-data'));
        $response->assertForbidden();
    }

    public function test_bpbd_can_get_aggregated_chart_data(): void
    {
        $bpbdUser = $this->createBpbdUser();

        $desa1 = Desa::create([
            'kode_desa' => '33.03.10.2001',
            'nama_desa' => 'Serang',
            'kecamatan' => 'Karangreja',
            'jumlah_kk_total' => 500,
            'jumlah_jiwa_total' => 2000,
            'jumlah_balita' => 30,
            'jumlah_lansia' => 40,
            'jumlah_disabilitas' => 5,
        ]);

        $desa2 = Desa::create([
            'kode_desa' => '33.03.04.2006',
            'nama_desa' => 'Selakambang',
            'kecamatan' => 'Kaligondang',
            'jumlah_kk_total' => 400,
            'jumlah_jiwa_total' => 1500,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
        ]);

        // Pengajuan Karangreja (50 KK => min 4000 liter)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-CHART-001',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 1000,
            'alasan_pengajuan' => 'Kekeringan Karangreja',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-11-10',
        ]);

        // Pengajuan Karangreja 2 (100 KK => 100 * 100 = 10000 liter)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-CHART-002',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Selesai',
            'jumlah_kk' => 100,
            'hari_tanpa_air' => 7,
            'jarak_sumber_air' => 1500,
            'alasan_pengajuan' => 'Kekeringan Karangreja 2',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-11-12',
        ]);

        // Pengajuan Kaligondang (30 KK => min 4000 liter, tapi status Draf -> TIDAK boleh terhitung)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-CHART-003',
            'kode_desa' => $desa2->kode_desa,
            'status' => 'Draf',
            'jumlah_kk' => 30,
            'hari_tanpa_air' => 2,
            'jarak_sumber_air' => 400,
            'alasan_pengajuan' => 'Draf Kaligondang',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-11-14',
        ]);

        $response = $this->actingAs($bpbdUser)->getJson(route('admin.dashboard.chart-data'));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => ['kecamatan', 'jumlah_pengajuan', 'volume_air_liter'],
            ],
        ]);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Karangreja', $data[0]['kecamatan']);
        $this->assertEquals(2, $data[0]['jumlah_pengajuan']);
        $this->assertEquals(15000, $data[0]['volume_air_liter']); // 5000 (min 4000 for 50 kk? 50*100=5000 > 4000) + 10000 = 15000
    }

    public function test_chart_filter_by_date_and_kecamatan(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_chart_test2');

        $desa1 = Desa::create([
            'kode_desa' => '33.03.10.2001',
            'nama_desa' => 'Serang',
            'kecamatan' => 'Karangreja',
            'jumlah_kk_total' => 500,
            'jumlah_jiwa_total' => 2000,
            'jumlah_balita' => 30,
            'jumlah_lansia' => 40,
            'jumlah_disabilitas' => 5,
        ]);

        $desa2 = Desa::create([
            'kode_desa' => '33.03.04.2006',
            'nama_desa' => 'Selakambang',
            'kecamatan' => 'Kaligondang',
            'jumlah_kk_total' => 400,
            'jumlah_jiwa_total' => 1500,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
        ]);

        Pengajuan::create([
            'nomor_tiket' => 'TKT-A',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 1000,
            'alasan_pengajuan' => 'November',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-11-05',
        ]);

        Pengajuan::create([
            'nomor_tiket' => 'TKT-B',
            'kode_desa' => $desa2->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 1000,
            'alasan_pengajuan' => 'Desember',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-12-01',
        ]);

        // Filter hanya bulan November
        $responseDate = $this->actingAs($bpbdUser)->getJson(route('admin.dashboard.chart-data', [
            'start_date' => '2026-11-01',
            'end_date' => '2026-11-30',
        ]));
        $responseDate->assertOk();
        $this->assertCount(1, $responseDate->json('data'));
        $this->assertEquals('Karangreja', $responseDate->json('data.0.kecamatan'));

        // Filter kecamatan Kaligondang
        $responseKec = $this->actingAs($bpbdUser)->getJson(route('admin.dashboard.chart-data', [
            'kecamatan' => 'Kaligondang',
        ]));
        $responseKec->assertOk();
        $this->assertCount(1, $responseKec->json('data'));
        $this->assertEquals('Kaligondang', $responseKec->json('data.0.kecamatan'));
    }

    public function test_invalid_date_range_fails_validation(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_chart_test3');

        $response = $this->actingAs($bpbdUser)->getJson(route('admin.dashboard.chart-data', [
            'start_date' => '2026-11-30',
            'end_date' => '2026-11-01', // start > end
        ]));

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['end_date']);
    }
}
