<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingProfileDesaTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_requires_all_five_demographic_values_before_linking_a_village(): void
    {
        $user = User::query()->create([
            'username' => 'desa-profile',
            'password' => 'password',
            'role' => 'desa',
        ]);
        Desa::query()->create([
            'kode_desa' => 'PROFILE-001',
            'nama_desa' => 'Desa Profil',
            'kecamatan' => 'Kecamatan Uji',
        ]);

        $this->actingAs($user)
            ->post(route('onboarding.profile.desa'), [
                'kode_desa' => 'PROFILE-001',
                'jumlah_kk_total' => 100,
                'jumlah_jiwa_total' => 400,
                'jumlah_balita' => 20,
                'jumlah_lansia' => 30,
            ])
            ->assertRedirect()
            ->assertSessionHasErrors(['jumlah_disabilitas']);

        $this->assertDatabaseHas('desas', [
            'kode_desa' => 'PROFILE-001',
            'user_id' => null,
            'jumlah_kk_total' => null,
        ]);
    }

    public function test_it_saves_required_demographics_and_links_the_village_to_the_account(): void
    {
        $user = User::query()->create([
            'username' => 'desa-profile-valid',
            'password' => 'password',
            'role' => 'desa',
        ]);
        Desa::query()->create([
            'kode_desa' => 'PROFILE-002',
            'nama_desa' => 'Desa Profil',
            'kecamatan' => 'Kecamatan Uji',
        ]);

        $this->actingAs($user)
            ->post(route('onboarding.profile.desa'), [
                'kode_desa' => 'PROFILE-002',
                'jumlah_kk_total' => 100,
                'jumlah_jiwa_total' => 400,
                'jumlah_balita' => 20,
                'jumlah_lansia' => 30,
                'jumlah_disabilitas' => 5,
            ])
            ->assertRedirect(route('desa.dashboard'));

        $this->assertDatabaseHas('desas', [
            'kode_desa' => 'PROFILE-002',
            'user_id' => $user->id,
            'jumlah_kk_total' => 100,
            'jumlah_jiwa_total' => 400,
            'jumlah_balita' => 20,
            'jumlah_lansia' => 30,
            'jumlah_disabilitas' => 5,
        ]);
        $this->assertTrue($user->fresh()->hasCompletedOnboarding());
    }

    public function test_it_rejects_vulnerable_group_counts_over_total_population(): void
    {
        $user = User::query()->create([
            'username' => 'desa-profile-invalid',
            'password' => 'password',
            'role' => 'desa',
        ]);
        Desa::query()->create([
            'kode_desa' => 'PROFILE-003',
            'nama_desa' => 'Desa Profil',
            'kecamatan' => 'Kecamatan Uji',
        ]);

        $this->actingAs($user)
            ->from(route('onboarding.profile'))
            ->post(route('onboarding.profile.desa'), [
                'kode_desa' => 'PROFILE-003',
                'jumlah_kk_total' => 100,
                'jumlah_jiwa_total' => 40,
                'jumlah_balita' => 20,
                'jumlah_lansia' => 15,
                'jumlah_disabilitas' => 10,
            ])
            ->assertRedirect(route('onboarding.profile'))
            ->assertSessionHasErrors(['jumlah_disabilitas']);

        $this->assertDatabaseHas('desas', [
            'kode_desa' => 'PROFILE-003',
            'user_id' => null,
        ]);
    }
}
