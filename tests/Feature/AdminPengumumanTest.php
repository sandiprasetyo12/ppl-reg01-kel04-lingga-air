<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPengumumanTest extends TestCase
{
    use RefreshDatabase;

    private function createBpbdUser(string $username = 'bpbd_announcement_user'): User
    {
        return User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'bpbd',
            'phone' => '081234567890',
        ]);
    }

    private function createDesaUser(string $username = 'desa_announcement_user'): User
    {
        $user = User::create([
            'username' => $username,
            'password' => 'password123',
            'role' => 'desa',
            'phone' => '081234567891',
        ]);

        Desa::create([
            'kode_desa' => '33.03.10.2001',
            'user_id' => $user->id,
            'nama_desa' => 'Serang',
            'kecamatan' => 'Karangreja',
            'jumlah_kk_total' => 500,
            'jumlah_jiwa_total' => 2000,
            'jumlah_balita' => 30,
            'jumlah_lansia' => 40,
            'jumlah_disabilitas' => 5,
        ]);

        return $user;
    }

    public function test_guest_cannot_access_pengumuman_management(): void
    {
        $response = $this->get(route('admin.pengumuman.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_desa_role_cannot_access_admin_pengumuman(): void
    {
        $desaUser = $this->createDesaUser();

        $response = $this->actingAs($desaUser)->get(route('admin.pengumuman.index'));
        $response->assertForbidden();
    }

    public function test_bpbd_can_view_pengumuman_index_and_dashboard_table(): void
    {
        $bpbdUser = $this->createBpbdUser();

        Pengumuman::create([
            'judul' => 'Darurat Kekeringan Kecamatan Karangreja',
            'isi' => 'Instruksi dropping tangki air segera.',
            'tingkat_kelayakan' => 'kritis',
            'is_active' => true,
            'created_by' => $bpbdUser->id,
            'published_at' => now(),
        ]);

        $indexRes = $this->actingAs($bpbdUser)->get(route('admin.pengumuman.index'));
        $indexRes->assertOk();
        $indexRes->assertSee('Darurat Kekeringan Kecamatan Karangreja');

        $dashRes = $this->actingAs($bpbdUser)->get(route('admin.dashboard'));
        $dashRes->assertOk();
        $dashRes->assertSee('Darurat Kekeringan Kecamatan Karangreja');
    }

    public function test_bpbd_can_create_new_pengumuman(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_creator');

        $response = $this->actingAs($bpbdUser)->post(route('admin.pengumuman.store'), [
            'judul' => 'Maklumat Siaga Air Bersih Purbalingga',
            'isi' => 'Masyarakat diimbau menghemat pemakaian air.',
            'tingkat_kelayakan' => 'waspada',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pengumumen', [
            'judul' => 'Maklumat Siaga Air Bersih Purbalingga',
            'tingkat_kelayakan' => 'waspada',
            'is_active' => true,
        ]);
    }

    public function test_pengumuman_validation_fails_on_missing_fields(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_validator');

        $response = $this->actingAs($bpbdUser)->post(route('admin.pengumuman.store'), [
            'judul' => '',
            'isi' => '',
            'tingkat_kelayakan' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['judul', 'isi', 'tingkat_kelayakan']);
    }

    public function test_bpbd_can_toggle_pengumuman_status(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_toggler');

        $pengumuman = Pengumuman::create([
            'judul' => 'Pengumuman Status Toggle',
            'isi' => 'Isi pengumuman toggle.',
            'tingkat_kelayakan' => 'aman',
            'is_active' => true,
            'created_by' => $bpbdUser->id,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($bpbdUser)->patch(route('admin.pengumuman.toggle-aktif', $pengumuman));
        $response->assertSessionHas('success');

        $pengumuman->refresh();
        $this->assertFalse($pengumuman->is_active);

        // Toggle kembali ke aktif
        $response2 = $this->actingAs($bpbdUser)->patch(route('admin.pengumuman.toggle-aktif', $pengumuman));
        $response2->assertSessionHas('success');

        $pengumuman->refresh();
        $this->assertTrue($pengumuman->is_active);
    }

    public function test_bpbd_can_update_existing_pengumuman(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_editor');

        $pengumuman = Pengumuman::create([
            'judul' => 'Judul Sebelum Diedit',
            'isi' => 'Isi teks lama.',
            'tingkat_kelayakan' => 'waspada',
            'is_active' => true,
            'created_by' => $bpbdUser->id,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($bpbdUser)->put(route('admin.pengumuman.update', $pengumuman), [
            'judul' => 'Judul Setelah Diperbarui',
            'isi' => 'Isi teks baru yang telah direvisi.',
            'tingkat_kelayakan' => 'kritis',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.pengumuman.index'));
        $this->assertDatabaseHas('pengumumen', [
            'id' => $pengumuman->id,
            'judul' => 'Judul Setelah Diperbarui',
            'tingkat_kelayakan' => 'kritis',
        ]);
    }

    public function test_emergency_announcement_appears_on_desa_dashboard(): void
    {
        $bpbdUser = $this->createBpbdUser('bpbd_broadcaster');
        $desaUser = $this->createDesaUser('desa_viewer');

        Pengumuman::create([
            'judul' => 'Peringatan Darurat Droping Air Desa Serang',
            'isi' => 'Armada tangki disiapkan meluncur ke titik drop balai desa.',
            'tingkat_kelayakan' => 'kritis',
            'is_active' => true,
            'created_by' => $bpbdUser->id,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($desaUser)->get(route('desa.dashboard'));
        $response->assertOk();
        $response->assertSee('Peringatan Darurat Droping Air Desa Serang');
    }
}
