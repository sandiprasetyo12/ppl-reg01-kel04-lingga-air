<?php

namespace Tests\Feature;

use App\Models\BerkasPengajuan;
use App\Models\Desa;
use App\Models\Pengajuan;
use App\Models\RiwayatCekKelayakan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanBantuanAirTest extends TestCase
{
    use RefreshDatabase;

    protected User $userDesaA;
    protected Desa $desaA;
    protected RiwayatCekKelayakan $simulasiA;

    protected User $userDesaB;
    protected Desa $desaB;
    protected RiwayatCekKelayakan $simulasiB;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        // Setup Desa A (Tanalum - Status Kritis)
        $this->userDesaA = User::factory()->create([
            'username' => 'kades_tanalum_' . uniqid(),
            'role' => 'desa',
        ]);

        $this->desaA = Desa::create([
            'user_id' => $this->userDesaA->id,
            'kode_desa' => '330313' . rand(1000, 9999),
            'nama_desa' => 'Desa Tanalum',
            'kecamatan' => 'Rembang',
            'jumlah_kk' => 600,
            'jumlah_jiwa' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'nama_pj' => 'Budi Santoso',
            'no_wa' => '081234567890',
            'lokasi_drop_point' => 'Halaman Balai Desa Tanalum',
            'latitude' => -7.2845100,
            'longitude' => 109.4321000,
        ]);

        $this->simulasiA = RiwayatCekKelayakan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
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
            'status' => 'kritis',
        ]);

        // Setup Desa B (Bodas - Status Aman)
        $this->userDesaB = User::factory()->create([
            'username' => 'kades_bodas_' . uniqid(),
            'role' => 'desa',
        ]);

        $this->desaB = Desa::create([
            'user_id' => $this->userDesaB->id,
            'kode_desa' => '33.03.17.' . rand(1000, 9999),
            'nama_desa' => 'Desa Bodas',
            'kecamatan' => 'Karangjambu',
            'jumlah_kk_total' => 400,
            'jumlah_jiwa_total' => 1500,
            'jumlah_balita' => 40,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
            'nama_pj_darurat' => 'Slamet',
            'no_wa_darurat' => '081543219876',
            'titik_drop_air' => 'Balai RW 02',
            'latitude' => -7.2201000,
            'longitude' => 109.4102000,
        ]);

        $this->simulasiB = RiwayatCekKelayakan::create([
            'kode_desa' => $this->desaB->kode_desa,
            'jumlah_kk_total' => 400,
            'jumlah_jiwa_total' => 1500,
            'jumlah_balita' => 40,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
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
            'status' => 'aman',
        ]);
    }

    /**
     * PBI-007: Formulir terisi otomatis dari hasil cek kelayakan
     * Tidak menampilkan label "* Wajib diisi" pada header data otomatis
     */
    public function test_formulir_pengajuan_terisi_otomatis_dari_hasil_cek_kelayakan(): void
    {
        $response = $this->actingAs($this->userDesaA)->get(route('pengajuan.create'));

        $response->assertStatus(200);
        $response->assertSee('Desa Tanalum');
        $response->assertSee('92.4');
        $response->assertSee('Kritis');
        $response->assertSee('120');
        $response->assertSee('14');
        $response->assertSee('1200');
        $response->assertDontSee('* Wajib diisi');
        $response->assertSee('Data tersinkron dari Cek Kelayakan');
    }

    /**
     * PBI-007: Pemohon menyusun draf pengajuan beserta foto pendukung dan surat kades
     */
    public function test_pemohon_dapat_menyusun_dan_menyimpan_draf_pengajuan(): void
    {
        $foto1 = UploadedFile::fake()->image('kondisi1.jpg', 600, 400)->size(1500);
        $foto2 = UploadedFile::fake()->image('kondisi2.png', 600, 400)->size(1200);
        $surat = UploadedFile::fake()->create('surat_kades.pdf', 500, 'application/pdf');

        $payload = [
            'action' => 'draf',
            'riwayat_id' => $this->simulasiA->id,
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Sumur dan sumber mata air desa mengering total akibat kemarau panjang.',
            'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum',
            'latitude' => -7.2845100,
            'longitude' => 109.4321000,
            'catatan_tambahan' => 'Akses truk tangki melalui jalan utama beraspal.',
            'foto_lapangan' => [$foto1, $foto2],
            'surat_kepala_desa' => $surat,
        ];

        $response = $this->actingAs($this->userDesaA)->post(route('pengajuan.store'), $payload);

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengajuan', [
            'kode_desa' => $this->desaA->kode_desa,
            'status' => 'Draf',
            'nomor_tiket' => null,
            'jumlah_kk' => 120,
        ]);

        $pengajuan = Pengajuan::where('kode_desa', $this->desaA->kode_desa)->latest()->first();
        $this->assertEquals(2, $pengajuan->fotoKondisi()->count());
        $this->assertNotNull($pengajuan->suratKepalaDesa);
    }

    /**
     * PBI-007: Sistem hanya menerima foto JPG/PNG dan batas ukuran, serta menolak berkas tidak sesuai
     */
    public function test_sistem_menolak_foto_bukan_jpg_png_atau_melebihi_batas_ukuran(): void
    {
        // 1. Ekstensi tidak sesuai
        $invalidFile = UploadedFile::fake()->create('dokumen.txt', 100, 'text/plain');

        $payload = [
            'action' => 'draf',
            'jumlah_kk' => 50,
            'hari_tanpa_air' => 7,
            'jarak_sumber_air' => 800,
            'alasan_pengajuan' => 'Air sumur menyusut drastis.',
            'foto_lapangan' => [$invalidFile],
        ];

        $response = $this->actingAs($this->userDesaA)->post(route('pengajuan.store'), $payload);
        $response->assertSessionHasErrors(['foto_lapangan.0']);

        // 2. Ukuran melebihi 5 MB
        $oversizedFile = UploadedFile::fake()->image('foto_besar.jpg')->size(6000);
        $payload['foto_lapangan'] = [$oversizedFile];

        $response2 = $this->actingAs($this->userDesaA)->post(route('pengajuan.store'), $payload);
        $response2->assertSessionHasErrors(['foto_lapangan.0']);
    }

    /**
     * PBI-008: Perangkat desa dapat melihat draf milik desanya
     */
    public function test_perangkat_desa_dapat_melihat_draf_milik_desanya(): void
    {
        $draf = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Kekeringan parah di dusun krajan.',
        ]);

        $response = $this->actingAs($this->userDesaA)->get(route('pengajuan.show', $draf->id));
        $response->assertStatus(200);
        $response->assertSee('Draf #' . str_pad($draf->id, 4, '0', STR_PAD_LEFT));
        $response->assertSee('Ubah Draf');
        $response->assertSee('Kirim Pengajuan');
    }

    /**
     * PBI-008: Draf desa lain tidak terlihat oleh perangkat desa berbeda (Policy)
     */
    public function test_draf_desa_lain_tidak_terlihat_oleh_desa_berbeda(): void
    {
        $drafA = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Kekeringan Desa A.',
        ]);

        // User Desa B mencoba melihat draf Desa A
        $response = $this->actingAs($this->userDesaB)->get(route('pengajuan.show', $drafA->id));
        $response->assertStatus(403);

        // Dan di halaman index Desa B, draf Desa A tidak muncul
        $indexResponse = $this->actingAs($this->userDesaB)->get(route('pengajuan.index'));
        $indexResponse->assertDontSee('#DRAF-' . str_pad($drafA->id, 4, '0', STR_PAD_LEFT));
    }

    /**
     * PBI-008: Perangkat desa dapat mengubah draf sebelum dikirim
     */
    public function test_perangkat_desa_dapat_mengubah_draf_sebelum_dikirim(): void
    {
        $draf = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Alasan awal sebelum direvisi.',
            'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum',
        ]);

        $updatePayload = [
            'action' => 'draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Alasan diperbarui setelah data warga diverifikasi kades.',
            'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum Baru (RT 03 RW 01)',
        ];

        $response = $this->actingAs($this->userDesaA)->put(route('pengajuan.update', $draf->id), $updatePayload);

        $response->assertRedirect(route('pengajuan.show', $draf->id));
        $this->assertDatabaseHas('pengajuan', [
            'id' => $draf->id,
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Alasan diperbarui setelah data warga diverifikasi kades.',
            'lokasi_pengantaran' => 'Halaman Balai Desa Tanalum Baru (RT 03 RW 01)',
        ]);
    }

    /**
     * Acceptance Criteria: Parameter kebutuhan terkunci dan readonly dari Cek Kelayakan Mandiri
     */
    public function test_parameter_kebutuhan_terkunci_dan_readonly_pada_formulir_dan_draf(): void
    {
        // 1. Cek pada form pembuatan pengajuan baru
        $responseCreate = $this->actingAs($this->userDesaA)->get(route('pengajuan.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Parameter Kebutuhan (Terkunci dari Cek Kelayakan F-02)');
        $responseCreate->assertSee('readonly', false);
        $responseCreate->assertSee('jumlah_kk_display');
        $responseCreate->assertSee('hari_tanpa_air_display');
        $responseCreate->assertSee('jarak_sumber_air_display');

        // 2. Cek pada form ubah draf
        $draf = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Draf uji coba kelayakan.',
        ]);

        $responseEdit = $this->actingAs($this->userDesaA)->get(route('pengajuan.edit', $draf->id));
        $responseEdit->assertStatus(200);
        $responseEdit->assertSee('Parameter Kebutuhan (Terkunci dari Cek Kelayakan F-02)');
        $responseEdit->assertSee('readonly', false);
        $responseEdit->assertSee('Nilai Terkunci Otomatis');
    }

    /**
     * PBI-008: Perangkat desa dapat menghapus draf sebelum dikirim
     */
    public function test_perangkat_desa_dapat_menghapus_draf_sebelum_dikirim(): void
    {
        $draf = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 80,
            'hari_tanpa_air' => 7,
            'jarak_sumber_air' => 900,
            'alasan_pengajuan' => 'Draf yang salah input.',
        ]);

        $response = $this->actingAs($this->userDesaA)->delete(route('pengajuan.destroy', $draf->id));

        $response->assertRedirect(route('pengajuan.index'));
        $this->assertDatabaseMissing('pengajuan', ['id' => $draf->id]);
    }

    /**
     * PBI-008: Draf yang sudah dikirim tidak dapat diubah atau dihapus
     */
    public function test_draf_yang_sudah_dikirim_tidak_dapat_diubah_atau_dihapus(): void
    {
        $pengajuanTerkirim = Pengajuan::create([
            'nomor_tiket' => 'LA-20261020-001',
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Diajukan',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Pengajuan resmi sudah terkirim.',
            'tanggal_pengajuan' => now(),
        ]);

        // Coba edit form -> 403
        $editResponse = $this->actingAs($this->userDesaA)->get(route('pengajuan.edit', $pengajuanTerkirim->id));
        $editResponse->assertStatus(403);

        // Coba update via PUT -> 403
        $updateResponse = $this->actingAs($this->userDesaA)->put(route('pengajuan.update', $pengajuanTerkirim->id), [
            'action' => 'draf',
            'jumlah_kk' => 200,
            'hari_tanpa_air' => 20,
            'jarak_sumber_air' => 1000,
            'alasan_pengajuan' => 'Coba ubah status terkirim',
        ]);
        $updateResponse->assertStatus(403);

        // Coba delete via DELETE -> 403
        $deleteResponse = $this->actingAs($this->userDesaA)->delete(route('pengajuan.destroy', $pengajuanTerkirim->id));
        $deleteResponse->assertStatus(403);
    }

    /**
     * Revisi 1 & PBI-009: Desa dengan status kelayakan Aman tidak dapat mengakses formulir pengajuan dan langsung diarahkan dengan pop-up modal
     */
    public function test_desa_dengan_status_kelayakan_aman_tidak_dapat_mengakses_halaman_pengajuan_dan_muncul_modal_pop_up(): void
    {
        // User Desa B (Desa Bodas, status riwayat kelayakan Aman) mencoba membuka halaman /pengajuan/create
        $response = $this->actingAs($this->userDesaB)->get(route('pengajuan.create'));

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('modal_kelayakan_aman');

        // Saat redirect diikuti ke halaman index, pop-up modal kelayakan aman muncul
        $indexResponse = $this->followRedirects($response);
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('modalKelayakanAman');
        $indexResponse->assertSee('Status Cek Kelayakan: AMAN');
        $indexResponse->assertSee('Pengajuan Tidak Dapat Diproses');
        $indexResponse->assertSee('Kondisi Terkendali');
    }

    /**
     * PBI-009: Sistem memvalidasi hasil cek berstatus Kritis/Waspada sebelum dapat dikirim (menolak status Aman)
     */
    public function test_sistem_menolak_pengiriman_jika_status_kelayakan_aman(): void
    {
        $drafBodas = Pengajuan::create([
            'kode_desa' => $this->desaB->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiB->id, // Status Aman (Skor 48.10)
            'status' => 'Draf',
            'jumlah_kk' => 20,
            'hari_tanpa_air' => 2,
            'jarak_sumber_air' => 200,
            'alasan_pengajuan' => 'Mencoba mengajukan padahal kondisi masih aman.',
        ]);

        // Tambah 1 foto agar memenuhi syarat foto
        BerkasPengajuan::create([
            'pengajuan_id' => $drafBodas->id,
            'jenis_berkas' => 'foto_kondisi',
            'nama_file' => 'test.jpg',
            'path_file' => 'pengajuan/foto/test.jpg',
            'mime_type' => 'image/jpeg',
            'ukuran_file' => 1024,
        ]);

        $response = $this->actingAs($this->userDesaB)->post(route('pengajuan.submit', $drafBodas->id));

        $response->assertSessionHasErrors(['kelayakan']);
        $this->assertEquals('Draf', $drafBodas->fresh()->status);
    }

    /**
     * PBI-009: Sistem menerbitkan nomor tiket unik, status Diajukan, dan menempatkan di antrean Admin
     */
    public function test_sistem_menerbitkan_nomor_tiket_unik_dan_status_diajukan_saat_dikirim(): void
    {
        $draf = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Desa membutuhkan bantuan segera.',
        ]);

        // Lampirkan 1 foto kondisi
        BerkasPengajuan::create([
            'pengajuan_id' => $draf->id,
            'jenis_berkas' => 'foto_kondisi',
            'nama_file' => 'lapangan.jpg',
            'path_file' => 'pengajuan/foto/lapangan.jpg',
            'mime_type' => 'image/jpeg',
            'ukuran_file' => 2048,
        ]);

        $response = $this->actingAs($this->userDesaA)->post(route('pengajuan.submit', $draf->id));

        $draf->refresh();
        $response->assertRedirect(route('pengajuan.success', $draf->id));

        $this->assertEquals('Diajukan', $draf->status);
        $this->assertNotNull($draf->nomor_tiket);
        $this->assertMatchesRegularExpression('/^LA-\d{8}-\d{3}$/', $draf->nomor_tiket);
        $this->assertNotNull($draf->tanggal_pengajuan);

        // Halaman berhasil dikirim dapat dibuka
        $successResponse = $this->actingAs($this->userDesaA)->get(route('pengajuan.success', $draf->id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Pengajuan Berhasil Dikirim');
        $successResponse->assertSee($draf->nomor_tiket);
    }

    /**
     * PBI-009: Sistem menolak pengajuan baru jika desa sudah punya tiket aktif (Anti-Spam)
     */
    public function test_sistem_menolak_pengajuan_baru_jika_desa_sudah_punya_tiket_aktif(): void
    {
        // 1. Buat tiket aktif yang sudah diajukan
        Pengajuan::create([
            'nomor_tiket' => 'LA-20261020-001',
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Diajukan',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Tiket aktif pertama.',
            'tanggal_pengajuan' => now(),
        ]);

        // 2. Coba kirim draf kedua
        $drafKedua = Pengajuan::create([
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_cek_kelayakan_id' => $this->simulasiA->id,
            'status' => 'Draf',
            'jumlah_kk' => 120,
            'hari_tanpa_air' => 14,
            'jarak_sumber_air' => 1200,
            'alasan_pengajuan' => 'Mencoba kirim permohonan kedua saat tiket pertama masih aktif.',
        ]);

        BerkasPengajuan::create([
            'pengajuan_id' => $drafKedua->id,
            'jenis_berkas' => 'foto_kondisi',
            'nama_file' => 'foto2.jpg',
            'path_file' => 'pengajuan/foto/foto2.jpg',
            'mime_type' => 'image/jpeg',
            'ukuran_file' => 2048,
        ]);

        $response = $this->actingAs($this->userDesaA)->post(route('pengajuan.submit', $drafKedua->id));

        $response->assertSessionHasErrors(['tiket_aktif']);
        $this->assertEquals('Draf', $drafKedua->fresh()->status);
    }

    /**
     * Integrasi Alur Cek Kelayakan (F-02) ke Pengajuan Bantuan Air (F-03)
     */
    public function test_alur_cek_kelayakan_terintegrasi_dengan_pengajuan_bantuan_air(): void
    {
        // 1. Akses halaman index pengajuan tanpa login -> Berhasil 200
        $responseIndex = $this->get(route('pengajuan.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Draf & Status Pengajuan', false);

        // 2. Akses halaman buat pengajuan dengan riwayat Desa B (status aman) -> ditolak modal pop-up
        $responseCreateAman = $this->get(route('pengajuan.create', ['kode_desa' => $this->desaB->kode_desa]));
        $responseCreateAman->assertRedirect(route('pengajuan.index'));
        $responseCreateAman->assertSessionHas('modal_kelayakan_aman');

        // 3. Akses halaman buat pengajuan dengan riwayat Desa A (status kritis) via query riwayat_id -> Berhasil 200
        $responseCreateKritis = $this->get(route('pengajuan.create', [
            'kode_desa' => $this->desaA->kode_desa,
            'riwayat_id' => $this->simulasiA->id,
        ]));
        $responseCreateKritis->assertStatus(200);
        $responseCreateKritis->assertSee('Pengajuan Bantuan Air Bersih');
        $responseCreateKritis->assertSee('120'); // KK terdampak prefilled
        $responseCreateKritis->assertSee('14');  // Hari tanpa air prefilled
        $responseCreateKritis->assertSee('1200'); // Jarak sumber air prefilled
    }
}
