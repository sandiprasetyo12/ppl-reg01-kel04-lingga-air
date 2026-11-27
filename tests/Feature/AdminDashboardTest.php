<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createBpbdUser(string $username = 'bpbd_dashboard_test'): User
    {
        return User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'bpbd',
            'phone' => '081234567890',
        ]);
    }

    private function createDesaUser(string $username = 'desa_dashboard_test'): User
    {
        $user = User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'desa',
            'phone' => '081234567891',
        ]);

        Desa::create([
            'kode_desa' => '33.03.99.1234',
            'user_id' => $user->id,
            'nama_desa' => 'Desa Terdaftar',
            'kecamatan' => 'Kemangkon',
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 5,
            'jumlah_lansia' => 10,
            'jumlah_disabilitas' => 1,
        ]);

        return $user;
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_desa_role_cannot_access_admin_dashboard(): void
    {
        $desaUser = $this->createDesaUser();

        $response = $this->actingAs($desaUser)->get('/admin/dashboard');
        $response->assertForbidden();
    }

    public function test_bpbd_can_access_dashboard_and_see_metric_cards(): void
    {
        $bpbdUser = $this->createBpbdUser();

        // Siapkan data desa
        $desa1 = Desa::create([
            'kode_desa' => '33.03.01.2001',
            'nama_desa' => 'Desa A',
            'kecamatan' => 'Kemangkon',
            'jumlah_kk_total' => 200,
            'jumlah_jiwa_total' => 800,
            'jumlah_balita' => 10,
            'jumlah_lansia' => 15,
            'jumlah_disabilitas' => 2,
        ]);

        $desa2 = Desa::create([
            'kode_desa' => '33.03.01.2002',
            'nama_desa' => 'Desa B',
            'kecamatan' => 'Kemangkon',
            'jumlah_kk_total' => 150,
            'jumlah_jiwa_total' => 600,
            'jumlah_balita' => 5,
            'jumlah_lansia' => 10,
            'jumlah_disabilitas' => 1,
        ]);

        // Siapkan data pengajuan dengan berbagai status
        // 1. Draf (tidak dihitung dalam agregasi totalPengajuan)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-TEST-001',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Draf',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 3,
            'jarak_sumber_air' => 500,
            'alasan_pengajuan' => 'Sumur mengering',
            'lokasi_pengantaran' => 'Balai Desa',
            'tanggal_pengajuan' => now(),
        ]);

        // 2. Diajukan (menunggu verifikasi)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-TEST-002',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 60,
            'hari_tanpa_air' => 4,
            'jarak_sumber_air' => 600,
            'alasan_pengajuan' => 'Debit mata air menurun drastis',
            'lokasi_pengantaran' => 'Balai Desa',
            'tanggal_pengajuan' => now(),
        ]);

        // 3. Dalam Telaah (menunggu verifikasi)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-TEST-003',
            'kode_desa' => $desa2->kode_desa,
            'status' => 'Dalam Telaah',
            'jumlah_kk' => 70,
            'hari_tanpa_air' => 5,
            'jarak_sumber_air' => 700,
            'alasan_pengajuan' => 'Kekeringan parah',
            'lokasi_pengantaran' => 'Tandon RW 1',
            'tanggal_pengajuan' => now(),
        ]);

        // 4. Selesai (distribusi selesai)
        Pengajuan::create([
            'nomor_tiket' => 'TKT-TEST-004',
            'kode_desa' => $desa1->kode_desa,
            'status' => 'Selesai',
            'jumlah_kk' => 80,
            'hari_tanpa_air' => 6,
            'jarak_sumber_air' => 800,
            'alasan_pengajuan' => 'Pasokan air bersih terhenti',
            'lokasi_pengantaran' => 'Balai Desa',
            'tanggal_pengajuan' => now(),
        ]);

        $response = $this->actingAs($bpbdUser)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');

        // Pastikan variabel terbawa ke view
        $response->assertViewHas('totalPengajuan', 3); // 3 non-draf
        $response->assertViewHas('desaTerdampak', 2);  // desa1 & desa2
        $response->assertViewHas('menungguVerifikasi', 2); // Diajukan & Dalam Telaah
        $response->assertViewHas('distribusiSelesai', 1);  // Selesai
        $response->assertViewHas('totalDesa', 2);

        // Pastikan teks kartu statistik dirender
        $response->assertSee('Total pengajuan');
        $response->assertSee('Menunggu verifikasi');
        $response->assertSee('Desa terdampak');
        $response->assertSee('Penyaluran selesai');
    }

    public function test_bpbd_can_filter_dashboard_by_month(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_filter_test');

        $desa = Desa::create([
            'kode_desa' => '33.03.02.2001',
            'nama_desa' => 'Desa Filter',
            'kecamatan' => 'Bobotsari',
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 5,
            'jumlah_lansia' => 10,
            'jumlah_disabilitas' => 1,
        ]);

        // Pengajuan di bulan November 2026
        Pengajuan::create([
            'nomor_tiket' => 'TKT-NOV-001',
            'kode_desa' => $desa->kode_desa,
            'status' => 'Diajukan',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 3,
            'jarak_sumber_air' => 500,
            'alasan_pengajuan' => 'Bulan November',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-11-15',
        ]);

        // Pengajuan di bulan Oktober 2026
        Pengajuan::create([
            'nomor_tiket' => 'TKT-OKT-001',
            'kode_desa' => $desa->kode_desa,
            'status' => 'Selesai',
            'jumlah_kk' => 40,
            'hari_tanpa_air' => 2,
            'jarak_sumber_air' => 300,
            'alasan_pengajuan' => 'Bulan Oktober',
            'lokasi_pengantaran' => 'Balai',
            'tanggal_pengajuan' => '2026-10-10',
        ]);

        // Filter ke bulan November 2026
        $responseNov = $this->actingAs($bpbdUser)->get('/admin/dashboard?bulan=2026-11');
        $responseNov->assertOk();
        $responseNov->assertViewHas('totalPengajuan', 1);
        $responseNov->assertViewHas('menungguVerifikasi', 1);
        $responseNov->assertViewHas('distribusiSelesai', 0);
        $responseNov->assertViewHas('selectedBulan', '2026-11');

        // Filter ke bulan Oktober 2026
        $responseOkt = $this->actingAs($bpbdUser)->get('/admin/dashboard?bulan=2026-10');
        $responseOkt->assertOk();
        $responseOkt->assertViewHas('totalPengajuan', 1);
        $responseOkt->assertViewHas('menungguVerifikasi', 0);
        $responseOkt->assertViewHas('distribusiSelesai', 1);
        $responseOkt->assertViewHas('selectedBulan', '2026-10');
    }
}
