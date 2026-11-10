<?php

namespace Tests\Feature;

use App\Models\Desa;
use Database\Seeders\DesaDemografiDummySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesaDemografiDummySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_consistent_dummy_demographics_without_overwriting_existing_data(): void
    {
        Desa::query()->create([
            'kode_desa' => 'DUMMY-001',
            'nama_desa' => 'Desa Dummy',
            'kecamatan' => 'Kecamatan Uji',
        ]);
        Desa::query()->create([
            'kode_desa' => 'REAL-001',
            'nama_desa' => 'Desa Terisi',
            'kecamatan' => 'Kecamatan Uji',
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
        ]);

        app(DesaDemografiDummySeeder::class)->run();

        $dummyDesa = Desa::query()->findOrFail('DUMMY-001');
        $this->assertGreaterThan(0, $dummyDesa->jumlah_kk_total);
        $this->assertGreaterThanOrEqual($dummyDesa->jumlah_kk_total, $dummyDesa->jumlah_jiwa_total);
        $this->assertGreaterThanOrEqual(
            $dummyDesa->jumlah_balita + $dummyDesa->jumlah_lansia + $dummyDesa->jumlah_disabilitas,
            $dummyDesa->jumlah_jiwa_total,
        );
        $this->assertTrue((bool) $dummyDesa->data_kependudukan_dummy);

        $existingDesa = Desa::query()->findOrFail('REAL-001');
        $this->assertSame(100, $existingDesa->jumlah_kk_total);
        $this->assertSame(400, $existingDesa->jumlah_jiwa_total);
        $this->assertFalse((bool) $existingDesa->data_kependudukan_dummy);

        $originalValues = $dummyDesa->only([
            'jumlah_kk_total',
            'jumlah_jiwa_total',
            'jumlah_balita',
            'jumlah_lansia',
            'jumlah_disabilitas',
        ]);
        app(DesaDemografiDummySeeder::class)->run();
        $this->assertSame($originalValues, Desa::query()->findOrFail('DUMMY-001')->only(array_keys($originalValues)));
    }

    public function test_it_shows_demographic_descriptions_without_exposing_dummy_data_labels(): void
    {
        Desa::query()->create([
            'kode_desa' => 'DUMMY-002',
            'nama_desa' => 'Desa Simulasi',
            'kecamatan' => 'Kecamatan Uji',
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
            'data_kependudukan_dummy' => true,
        ]);

        $this->withSession(['_old_input' => [
            'kode_desa' => 'DUMMY-002',
            'kecamatan' => 'Kecamatan Uji',
        ]])
            ->get(route('cek-kelayakan.index'))
            ->assertOk()
            ->assertSee('Jumlah anak berusia di bawah lima tahun di desa.')
            ->assertSee('Jumlah penduduk lanjut usia di desa.')
            ->assertSee('Jumlah penduduk penyandang disabilitas di desa.')
            ->assertDontSee('dummy');
    }
}
