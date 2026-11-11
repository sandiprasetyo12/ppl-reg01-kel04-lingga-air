<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Penyalur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OnboardingController extends Controller
{
    // Tampilkan halaman welcome onboarding
    public function welcome()
    {
        // Kalau sudah selesai onboarding, langsung ke dashboard
        if (auth()->user()->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        return view('onboarding.welcome');
    }

    // Tampilkan halaman pilih role
    public function showRole()
    {
        $user = auth()->user();

        // Kalau sudah selesai onboarding, langsung ke dashboard
        if ($user->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        // Kalau sudah pilih role, langsung ke profil
        if (!is_null($user->role)) {
            return redirect()->route('onboarding.profile');
        }

        return view('onboarding.role');
    }

    // Proses simpan pilihan role
    public function storeRole(Request $request)
    {
        if (auth()->user()->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        // Validasi: hanya boleh desa atau penyalur (bpbd tidak boleh daftar sendiri)
        $validated = $request->validate([
            'role' => 'required|in:desa,penyalur',
        ]);

        // Simpan role ke user
        auth()->user()->update(['role' => $validated['role']]);

        // Lanjut ke step profil
        return redirect()->route('onboarding.profile');
    }


    // Tampilkan form profil sesuai role yang dipilih
    public function showProfile()
    {
        $user = auth()->user();

        // Kalau sudah selesai onboarding, langsung ke dashboard
        if ($user->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        // Kalau belum pilih role, balik ke step role
        if (is_null($user->role)) {
            return redirect()->route('onboarding.role');
        }

        // Tampilkan form sesuai role
        if ($user->isDesa()) {
            // Ambil daftar desa yang belum ada akun-nya
            $desaTersedia = Desa::whereNull('user_id')
                ->orderBy('kecamatan')
                ->orderBy('nama_desa')
                ->get();
            return view('onboarding.profile-desa', compact('desaTersedia'));
        }

        return view('onboarding.profile-penyalur');
    }

    // Proses simpan profil desa (PBI-001)
    public function storeProfileDesa(Request $request)
    {
        $user = auth()->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        if (!$user->isDesa()) {
            return redirect()->route('onboarding.profile');
        }

        // Validasi input profil desa (hanya desa yang belum tertaut akun lain)
        $validated = $request->validate([
            'kode_desa' => [
                'required',
                'string',
                Rule::exists('desas', 'kode_desa')->where(fn ($query) => $query->whereNull('user_id')),
            ],
            'jumlah_kk_total' => 'nullable|integer|min:0',
            'jumlah_jiwa_total' => 'nullable|integer|min:0',
            'jumlah_balita' => 'nullable|integer|min:0',
            'jumlah_lansia' => 'nullable|integer|min:0',
            'jumlah_disabilitas' => 'nullable|integer|min:0',
            'nama_pj_darurat' => 'nullable|string|max:100',
            'no_wa_darurat' => 'nullable|string|max:20',
            'titik_drop_air' => 'nullable|string|max:50',
        ]);

        // Link user ke desa yang dipilih & isi data kependudukan
        Desa::where('kode_desa', $validated['kode_desa'])
            ->whereNull('user_id')
            ->update([
                'user_id' => $user->id,
                'jumlah_kk_total' => $validated['jumlah_kk_total'] ?? null,
                'jumlah_jiwa_total' => $validated['jumlah_jiwa_total'] ?? null,
                'jumlah_balita' => $validated['jumlah_balita'] ?? null,
                'jumlah_lansia' => $validated['jumlah_lansia'] ?? null,
                'jumlah_disabilitas' => $validated['jumlah_disabilitas'] ?? null,
                'nama_pj_darurat' => $validated['nama_pj_darurat'] ?? null,
                'no_wa_darurat' => $validated['no_wa_darurat'] ?? null,
                'titik_drop_air' => $validated['titik_drop_air'] ?? null,
            ]);

        // Onboarding selesai → redirect ke dashboard desa
        return redirect()->route('desa.dashboard')->with('success', 'Selamat datang di Lingga Air!');
    }

    // Proses simpan profil penyalur (PBI-002)
    public function storeProfilePenyalur(Request $request)
    {
        $user = auth()->user();

        if ($user->hasCompletedOnboarding()) {
            return $this->redirectToDashboard();
        }

        if (!$user->isPenyalur()) {
            return redirect()->route('onboarding.profile');
        }

        // Validasi input profil penyalur (sesuai ERD)
        $validated = $request->validate([
            'nama_penyalur' => 'required|string|max:100',
            'kapasitas_tangki_liter' => 'required|integer|min:100',
            'no_telepon' => 'required|string|max:20',
        ]);

        // Buat profil armada penyalur
        Penyalur::create([
            'user_id' => $user->id,
            'nama_penyalur' => $validated['nama_penyalur'],
            'kapasitas_tangki_liter' => $validated['kapasitas_tangki_liter'],
            'no_telepon' => $validated['no_telepon'],
            'status' => 'aktif',
        ]);

        // Onboarding selesai → redirect ke dashboard penyalur
        return redirect()->route('penyalur.dashboard')->with('success', 'Selamat datang di Lingga Air!');
    }

   // Helper untuk redirect ke dashboard sesuai role
    private function redirectToDashboard()
    {
        return match (auth()->user()->role) {
            'bpbd' => redirect()->route('admin.dashboard'),
            'penyalur' => redirect()->route('penyalur.dashboard'),
            default => redirect()->route('desa.dashboard'),
        };
    }
}