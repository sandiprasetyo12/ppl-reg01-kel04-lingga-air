<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses register: buat akun baru tanpa role (role dipilih di onboarding)
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buat user baru (role masih null, diisi saat onboarding)
        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => null,
        ]);

        // Langsung login & arahkan ke onboarding
        Auth::login($user);

        return redirect()->route('onboarding.welcome');
    }

    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login: validasi username & password
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        // Coba autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Kalau belum selesai onboarding, arahkan ke onboarding
            if (!$user->hasCompletedOnboarding()) {
                $step = $user->currentOnboardingStep();
                return redirect()->route("onboarding.{$step}");
            }

            // Redirect ke dashboard sesuai role
            return match ($user->role) {
                'bpbd' => redirect()->route('admin.dashboard'),
                'penyalur' => redirect()->route('penyalur.dashboard'),
                default => redirect()->route('desa.dashboard'),
            };
        }

        // Gagal login
        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
    }

    // Proses logout: hapus session, redirect ke login
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}