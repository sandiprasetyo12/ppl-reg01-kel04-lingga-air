<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Penyalur;
use App\Models\Penyaluran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPenyaluranTest extends TestCase
{
    use RefreshDatabase;

    protected User $bpbdUser;
    protected User $pmiUser;
    protected Penyalur $penyalurPmi;
    protected User $pdamUser;
    protected Penyalur $penyalurPdam;
    protected Desa $desa1;
    protected Desa $desa2;

    protected function setUp(): void
    {
        parent::setUp();

        Penyaluran::ensureTableExists();

        // Setup BPBD User
        $this->bpbdUser = User::create([
            'username' => 'admin_bpbd_test',
            'password' => bcrypt('password'),
            'role' => 'bpbd',
            'phone' => '081234567890',
        ]);

        // Setup Penyalur 1: PMI
        $this->pmiUser = User::create([
            'username' => 'penyalur_pmi_test',
            'password' => bcrypt('password'),
            'role' => 'penyalur',
            'phone' => '081234567891',
        ]);
        $this->penyalurPmi = Penyalur::create([
            'user_id' => $this->pmiUser->id,
            'nama_penyalur' => 'PMI Purbalingga Test',
            'kapasitas_tangki_liter' => 5000,
            'no_telepon' => '0281-891001',
            'status' => 'aktif',
        ]);

        // Setup Penyalur 2: PDAM
        $this->pdamUser = User::create([
            'username' => 'penyalur_pdam_test',
            'password' => bcrypt('password'),
            'role' => 'penyalur',
            'phone' => '081234567892',
        ]);
        $this->penyalurPdam = Penyalur::create([
            'user_id' => $this->pdamUser->id,
            'nama_penyalur' => 'PDAM Purbalingga Test',
            'kapasitas_tangki_liter' => 5000,
            'no_telepon' => '0281-891002',
            'status' => 'aktif',
        ]);

        // Setup Desa
        $this->desa1 = Desa::firstOrCreate(
            ['kode_desa' => '33.03.11.2005'],
            [
                'nama_desa' => 'Serang',
                'kecamatan' => 'Karangreja',
            ]
        );
        $this->desa2 = Desa::firstOrCreate(
            ['kode_desa' => '33.03.04.2008'],
            [
                'nama_desa' => 'Selakambang',
                'kecamatan' => 'Kaligondang',
            ]
        );

        // Seed Deliveries
        Penyaluran::create([
            'kode_penyaluran' => 'TEST-PMI-001',
            'nomor_tiket' => 'TKT-TEST-001',
            'kode_desa' => $this->desa1->kode_desa,
            'penyalur_id' => $this->penyalurPmi->id,
            'tanggal_penyaluran' => Carbon::now()->subDays(2)->toDateString(),
            'volume_air_liter' => 5000,
            'jumlah_tangki' => 1,
            'status' => 'Terkirim',
            'titik_serah_terima' => 'Bak RT 01',
            'nama_penerima' => 'Pak Budi',
        ]);

        Penyaluran::create([
            'kode_penyaluran' => 'TEST-PDAM-001',
            'nomor_tiket' => 'TKT-TEST-002',
            'kode_desa' => $this->desa2->kode_desa,
            'penyalur_id' => $this->penyalurPdam->id,
            'tanggal_penyaluran' => Carbon::now()->subDays(1)->toDateString(),
            'volume_air_liter' => 10000,
            'jumlah_tangki' => 2,
            'status' => 'Terkirim',
            'titik_serah_terima' => 'Tandon RW 02',
            'nama_penerima' => 'Pak Joko',
        ]);
    }

    /**
     * PBI-022: BPBD dapat melihat rekapitulasi penyaluran dengan filter
     */
    public function test_bpbd_can_view_all_penyaluran_and_filter(): void
    {
        $response = $this->actingAs($this->bpbdUser)->get(route('laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('TEST-PMI-001');
        $response->assertSee('TEST-PDAM-001');
        $response->assertSee('Rekap Penyaluran Air Bersih');

        // Filter by penyalur
        $filterResponse = $this->actingAs($this->bpbdUser)->get(route('laporan.index', [
            'penyalur_id' => $this->penyalurPmi->id,
        ]));
        $filterResponse->assertStatus(200);
        $filterResponse->assertSee('TEST-PMI-001');
        $filterResponse->assertDontSee('TEST-PDAM-001');

        // Filter kosong menampilkan pesan data tidak tersedia (Acceptance Criteria 2)
        $emptyResponse = $this->actingAs($this->bpbdUser)->get(route('laporan.index', [
            'kecamatan' => 'KecamatanTidakAda',
        ]));
        $emptyResponse->assertStatus(200);
        $emptyResponse->assertSee('Data penyaluran tidak tersedia');
    }

    /**
     * PBI-023: Penyalur hanya dapat melihat penyaluran miliknya dan menolak akses data mitra lain
     */
    public function test_penyalur_can_only_view_own_penyaluran_and_cannot_access_others(): void
    {
        // PMI Login
        $response = $this->actingAs($this->pmiUser)->get(route('penyalur.laporan.index'));
        $response->assertStatus(200);
        $response->assertSee('TEST-PMI-001');
        $response->assertDontSee('TEST-PDAM-001');

        // Uji manipulasi URL parameter ?penyalur_id= milik penyalur lain
        $manipulatedResponse = $this->actingAs($this->pmiUser)->get(route('penyalur.laporan.index', [
            'penyalur_id' => $this->penyalurPdam->id,
        ]));
        $manipulatedResponse->assertStatus(200);
        // Tetap hanya melihat data miliknya sendiri (PMI), data PDAM ditolak / tidak tampil!
        $manipulatedResponse->assertSee('TEST-PMI-001');
        $manipulatedResponse->assertDontSee('TEST-PDAM-001');

        // PDAM Login
        $responsePdam = $this->actingAs($this->pdamUser)->get(route('penyalur.laporan.index'));
        $responsePdam->assertStatus(200);
        $responsePdam->assertSee('TEST-PDAM-001');
        $responsePdam->assertDontSee('TEST-PMI-001');
    }

    /**
     * PBI-024: BPBD dan Penyalur dapat mengekspor laporan ke format Excel dan PDF
     */
    public function test_export_excel_and_pdf(): void
    {
        // BPBD ekspor Excel
        $excelResponse = $this->actingAs($this->bpbdUser)->get(route('laporan.export', ['format' => 'excel']));
        $excelResponse->assertStatus(200);
        $excelResponse->assertHeader('Content-Disposition');
        $this->assertStringContainsString('attachment', $excelResponse->headers->get('Content-Disposition'));

        // BPBD ekspor PDF
        $pdfResponse = $this->actingAs($this->bpbdUser)->get(route('laporan.export', ['format' => 'pdf']));
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertSee('Laporan Rekapitulasi Penyaluran Bantuan Air Bersih Darurat');

        // Penyalur ekspor Excel (hanya miliknya)
        $penyalurExcel = $this->actingAs($this->pmiUser)->get(route('penyalur.laporan.export', ['format' => 'excel']));
        $penyalurExcel->assertStatus(200);
        $penyalurExcel->assertSee('TEST-PMI-001');
        $penyalurExcel->assertDontSee('TEST-PDAM-001');

        // Penyalur ekspor PDF (hanya miliknya)
        $penyalurPdf = $this->actingAs($this->pmiUser)->get(route('penyalur.laporan.export', ['format' => 'pdf']));
        $penyalurPdf->assertStatus(200);
        $penyalurPdf->assertSee('TEST-PMI-001');
        $penyalurPdf->assertDontSee('TEST-PDAM-001');
    }
}
