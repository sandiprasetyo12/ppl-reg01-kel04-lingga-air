<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\RiwayatCekKelayakan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CekKelayakanTest extends TestCase
{
    use RefreshDatabase;

    private string $testKodeDesa;

    public function test_eligibility_form_requires_a_logged_in_desa_account(): void
    {
        $this->get(route('cek-kelayakan.index'))
            ->assertRedirect(route('login'));
    }

    public function test_desa_dashboard_provides_a_link_to_eligibility_check(): void
    {
        $this->villageInput([
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
            'kk_terdampak' => 0,
            'hari_tanpa_air' => 0,
            'jarak_sumber_meter' => 0,
        ]);

        $response = $this->get(route('desa.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Periksa kelayakan bantuan air bersih')
            ->assertSee('Mulai Cek Kelayakan');

        $this->assertSame(
            2,
            substr_count($response->getContent(), 'href="'.route('cek-kelayakan.index').'"'),
        );
    }

    public function test_it_displays_the_authenticated_desa_profile_without_identity_selectors(): void
    {
        $this->villageInput([
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
            'kk_terdampak' => 0,
            'hari_tanpa_air' => 0,
            'jarak_sumber_meter' => 0,
        ]);

        $this->get(route('cek-kelayakan.index'))
            ->assertOk()
            ->assertSee('Cek Kelayakan Bantuan Air Bersih')
            ->assertSee('Profil desa')
            ->assertSee('Desa Uji')
            ->assertSee('Kecamatan Uji')
            ->assertDontSee('id="kecamatan"', false)
            ->assertDontSee('id="kode_desa"', false)
            ->assertSee('Portal pelayanan desa')
            ->assertSee('desa-test-1')
            ->assertSee('Desa Uji')
            ->assertSee('Navigasi desa')
            ->assertSee('Pengajuan Bantuan')
            ->assertSee('Status Pengajuan')
            ->assertDontSee('Profil Desa')
            ->assertSee('Bantuan &amp; Panduan', false)
            ->assertDontSee('Segera hadir')
            ->assertSee('class="layout no-result"', false)
            ->assertDontSee('Sebelum memeriksa kondisi')
            ->assertDontSee('Hasil Cek Kondisi');
    }

    public function test_it_returns_the_calculated_score_for_a_valid_submission(): void
    {
        $this->submitCalculation([
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'kk_terdampak' => 450,
            'hari_tanpa_air' => 5,
            'jarak_sumber_meter' => 2500,
        ])
            ->assertOk()
            ->assertSee('class="layout has-result"', false)
            ->assertDontSee('Sebelum memeriksa kondisi')
            ->assertSee('74,76')
            ->assertSee('Kritis')
            ->assertSee('class="application-action"', false)
            ->assertSee(route('pengajuan.create'), false)
            ->assertSee('Ajukan Pengajuan')
            ->assertSee('Lanjutkan untuk melengkapi data dan berkas pengajuan bantuan air.')
            ->assertSee('Segera ajukan bantuan air bersih dan siapkan surat permohonan Kepala Desa berstempel basah serta foto kondisi lapangan.')
            ->assertDontSee('Ajukan bantuan sekarang')
            ->assertDontSee('Cek ulang')
            ->assertDontSee('Kembali ke Beranda')
            ->assertDontSee('Hasil ini belum menjadi pengajuan dan bukan keputusan persetujuan bantuan.')
            ->assertDontSee('Data ilustratif')
            ->assertDontSee('C1 · Rasio KK terdampak')
            ->assertDontSee('C2 · Kelompok rentan')
            ->assertDontSee('C3 · Durasi tanpa air')
            ->assertDontSee('C4 · Jarak sumber air')
            ->assertDontSee('Simulasi F-02')
            ->assertDontSee('AHP-SAW')
            ->assertSee('Sumber air terdekat berjarak 2.500 meter, desa sudah 5 hari kesulitan air, dan 450 dari 600 KK (75%) terdampak.')
            ->assertDontSee('scale-track')
            ->assertDontSee('Kenapa hasilnya begini')
            ->assertDontSee('Yang perlu disiapkan')
            ->assertDontSee('Data tidak disimpan dalam simulasi ini');
    }

    public function test_it_displays_a_valid_result_within_five_seconds(): void
    {
        $startedAt = microtime(true);

        $response = $this->submitCalculation([
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'kk_terdampak' => 450,
            'hari_tanpa_air' => 5,
            'jarak_sumber_meter' => 2500,
        ]);

        $elapsedSeconds = microtime(true) - $startedAt;

        $response
            ->assertOk()
            ->assertSee('74,76')
            ->assertSee('Kritis');

        $this->assertLessThanOrEqual(
            5.0,
            $elapsedSeconds,
            sprintf('The valid eligibility result took %.3f seconds to render.', $elapsedSeconds),
        );
    }

    public function test_it_saves_the_form_snapshot_and_calculated_result_to_history(): void
    {
        $this->submitCalculation([
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'kk_terdampak' => 450,
            'hari_tanpa_air' => 5,
            'jarak_sumber_meter' => 2500,
        ])
            ->assertOk()
            ->assertSee('74,76')
            ->assertSee('Kritis');

        $this->assertDatabaseHas('riwayat_cek_kelayakan', [
            'kode_desa' => $this->testKodeDesa,
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 95,
            'jumlah_lansia' => 80,
            'jumlah_disabilitas' => 12,
            'kk_terdampak' => 450,
            'hari_tanpa_air' => 5,
            'jarak_sumber_meter' => 2500,
            'status' => 'kritis',
        ]);

        $history = RiwayatCekKelayakan::firstOrFail();
        $this->assertSame($this->testKodeDesa, $history->desa->kode_desa);
        $this->assertCount(1, $history->desa->riwayatCekKelayakan);
        $this->assertEqualsWithDelta(74.76, $history->skor, 0.01);
        $this->assertEqualsWithDelta(0.504736, $history->bobot_c1, 0.000001);
        $this->assertEqualsWithDelta(0.082644, $history->bobot_c2, 0.000001);
        $this->assertEqualsWithDelta(0.302604, $history->bobot_c3, 0.000001);
        $this->assertEqualsWithDelta(0.110017, $history->bobot_c4, 0.000001);
        $this->assertEqualsWithDelta(0.75, $history->c1, 0.000001);
        $this->assertEqualsWithDelta(0.519444, $history->c2, 0.000001);
        $this->assertEqualsWithDelta(0.714286, $history->c3, 0.000001);
        $this->assertEqualsWithDelta(1.0, $history->c4, 0.000001);
        $this->assertLessThan(0.10, $history->rasio_konsistensi);
    }

    public function test_it_shows_aman_guidance_without_submission_documents(): void
    {
        $this->submitCalculation([
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 0,
            'jumlah_lansia' => 0,
            'jumlah_disabilitas' => 0,
            'kk_terdampak' => 0,
            'hari_tanpa_air' => 0,
            'jarak_sumber_meter' => 0,
        ])
            ->assertOk()
            ->assertSee('Kondisi desa relatif terkendali.')
            ->assertSee('Belum perlu mengajukan bantuan; pantau ketersediaan air dan cek ulang bila kesulitan air bertambah.')
            ->assertDontSee('Ajukan Pengajuan')
            ->assertDontSee('Cek ulang')
            ->assertDontSee('Kembali ke Beranda')
            ->assertDontSee('Ajukan bantuan sekarang')
            ->assertDontSee('Yang perlu disiapkan');
    }

    public function test_it_shows_waspada_guidance_and_documents(): void
    {
        $this->submitCalculation([
            'jumlah_kk_total' => 600,
            'jumlah_jiwa_total' => 2400,
            'jumlah_balita' => 0,
            'jumlah_lansia' => 0,
            'jumlah_disabilitas' => 0,
            'kk_terdampak' => 180,
            'hari_tanpa_air' => 5,
            'jarak_sumber_meter' => 1000,
        ])
            ->assertOk()
            ->assertSee('Waspada')
            ->assertSee('class="application-action"', false)
            ->assertSee(route('pengajuan.create'), false)
            ->assertSee('Ajukan Pengajuan')
            ->assertSee('Lanjutkan untuk melengkapi data dan berkas pengajuan bantuan air.')
            ->assertDontSee('Lanjut ke Pengajuan')
            ->assertDontSee('Cek ulang')
            ->assertSee('Siapkan surat permohonan Kepala Desa berstempel basah dan foto kondisi lapangan. Anda dapat mengajukan sekarang atau memantau dulu.')
            ->assertDontSee('Halaman pengajuan belum tersedia di aplikasi ini.');
    }

    public function test_it_explains_the_condition_using_the_submitted_field_values(): void
    {
        $this->submitCalculation([
            'jumlah_kk_total' => 250,
            'jumlah_jiwa_total' => 1000,
            'jumlah_balita' => 0,
            'jumlah_lansia' => 0,
            'jumlah_disabilitas' => 0,
            'kk_terdampak' => 26,
            'hari_tanpa_air' => 4,
            'jarak_sumber_meter' => 1500,
        ])
            ->assertOk()
            ->assertSee('Tingkat kedaruratan')
            ->assertSee('Sumber air terdekat berjarak 1.500 meter, desa sudah 4 hari kesulitan air, dan 26 dari 250 KK (10%) terdampak.');
    }

    public function test_it_rejects_input_that_exceeds_the_reference_totals(): void
    {
        $this->from(route('cek-kelayakan.index'))
            ->post(route('cek-kelayakan.calculate'), $this->villageInput([
                'jumlah_kk_total' => 10,
                'jumlah_jiwa_total' => 10,
                'jumlah_balita' => 1,
                'jumlah_lansia' => 1,
                'jumlah_disabilitas' => 1,
                'kk_terdampak' => 11,
                'hari_tanpa_air' => 0,
                'jarak_sumber_meter' => 0,
            ]))
            ->assertRedirect(route('cek-kelayakan.index'))
            ->assertSessionHasErrors(['kk_terdampak']);
    }

    public function test_it_rejects_zero_denominators_and_negative_values(): void
    {
        $this->from(route('cek-kelayakan.index'))
            ->post(route('cek-kelayakan.calculate'), $this->villageInput([
                'jumlah_kk_total' => 100,
                'jumlah_jiwa_total' => 400,
                'jumlah_balita' => 20,
                'jumlah_lansia' => 30,
                'jumlah_disabilitas' => 4,
                'kk_terdampak' => -1,
                'hari_tanpa_air' => 0,
                'jarak_sumber_meter' => 0,
            ]))
            ->assertRedirect(route('cek-kelayakan.index'))
            ->assertSessionHasErrors(['kk_terdampak']);
    }

    public function test_incomplete_profile_cannot_open_eligibility_form(): void
    {
        $user = User::query()->create([
            'username' => 'desa-incomplete',
            'password' => 'password',
            'role' => 'desa',
        ]);
        Desa::query()->create([
            'kode_desa' => 'TEST-NULL',
            'user_id' => $user->id,
            'nama_desa' => 'Desa Belum Lengkap',
            'kecamatan' => 'Kecamatan Uji',
            'jumlah_kk_total' => null,
            'jumlah_jiwa_total' => null,
            'jumlah_balita' => null,
            'jumlah_lansia' => null,
            'jumlah_disabilitas' => null,
        ]);

        $this->actingAs($user)
            ->get(route('cek-kelayakan.index'))
            ->assertRedirect(route('onboarding.profile'));
    }

    public function test_calculation_uses_the_authenticated_village_even_if_another_village_is_submitted(): void
    {
        $input = $this->villageInput([
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
            'kk_terdampak' => 50,
            'hari_tanpa_air' => 2,
            'jarak_sumber_meter' => 100,
        ]);
        Desa::query()->create([
            'kode_desa' => 'TEST-OTHER',
            'nama_desa' => 'Desa Lain',
            'kecamatan' => 'Kecamatan Lain',
        ]);
        $input['kode_desa'] = 'TEST-OTHER';
        $input['kecamatan'] = 'Kecamatan Lain';

        $this->post(route('cek-kelayakan.calculate'), $input)
            ->assertOk()
            ->assertSee('Desa Uji');

        $this->assertDatabaseHas('riwayat_cek_kelayakan', [
            'kode_desa' => $this->testKodeDesa,
        ]);
        $this->assertDatabaseMissing('riwayat_cek_kelayakan', [
            'kode_desa' => 'TEST-OTHER',
        ]);
    }

    public function test_it_displays_desa_profile_values_as_disabled_fields(): void
    {
        $this->villageInput([
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 4,
            'kk_terdampak' => 0,
            'hari_tanpa_air' => 0,
            'jarak_sumber_meter' => 0,
        ]);

        $this->get(route('cek-kelayakan.index'))
            ->assertOk()
            ->assertSee('value="100" disabled aria-disabled="true"', false)
            ->assertSee('value="400" disabled aria-disabled="true"', false)
            ->assertDontSee('name="kode_desa"', false)
            ->assertDontSee('name="kecamatan"', false);
    }

    /**
     * @param  array<string, int>  $input
     */
    private function submitCalculation(array $input)
    {
        return $this->post(
            route('cek-kelayakan.calculate'),
            $this->villageInput($input),
        );
    }

    /**
     * @param  array<string, int>  $input
     * @return array<string, int|string>
     */
    private function villageInput(array $input): array
    {
        $this->testKodeDesa = 'TEST-'.(Desa::query()->count() + 1);
        $user = User::query()->create([
            'username' => 'desa-test-'.(User::query()->count() + 1),
            'password' => 'password',
            'role' => 'desa',
        ]);
        Desa::query()->create([
            'kode_desa' => $this->testKodeDesa,
            'user_id' => $user->id,
            'nama_desa' => 'Desa Uji',
            'kecamatan' => 'Kecamatan Uji',
            'jumlah_kk_total' => $input['jumlah_kk_total'],
            'jumlah_jiwa_total' => $input['jumlah_jiwa_total'],
            'jumlah_balita' => $input['jumlah_balita'],
            'jumlah_lansia' => $input['jumlah_lansia'],
            'jumlah_disabilitas' => $input['jumlah_disabilitas'],
        ]);

        unset(
            $input['jumlah_kk_total'],
            $input['jumlah_jiwa_total'],
            $input['jumlah_balita'],
            $input['jumlah_lansia'],
            $input['jumlah_disabilitas'],
        );

        $this->actingAs($user);

        return $input;
    }
}
