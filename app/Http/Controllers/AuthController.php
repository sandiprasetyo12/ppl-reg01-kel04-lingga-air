<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan form login (Mockup halaman Masuk)
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('pengajuan.index');
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi login
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau Kode Desa wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        // Dukungan login menggunakan email atau kode desa
        $user = null;
        if (filter_var($credentials['email'], FILTER_VALIDATE_EMAIL)) {
            $attemptCredentials = ['email' => $credentials['email'], 'password' => $credentials['password']];
        } else {
            // Cek jika yang diinput adalah kode desa
            $userByKode = User::whereHas('desa', function ($q) use ($credentials) {
                $q->where('kode_desa', $credentials['email']);
            })->first();

            if ($userByKode) {
                $attemptCredentials = ['email' => $userByKode->email, 'password' => $credentials['password']];
            } else {
                $attemptCredentials = ['email' => $credentials['email'], 'password' => $credentials['password']];
            }
        }

        if (Auth::attempt($attemptCredentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('pengajuan.index'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Kombinasi akun dan kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    /**
     * Logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah berhasil keluar.');
    }

    /**
     * Pintasan switch user untuk kemudahan testing / simulasi peran
     */
    public function switchUser(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        Auth::login($user);

        return redirect()->route('pengajuan.index')
            ->with('info', 'Beralih ke akun: ' . $user->name . ' (' . ucfirst($user->role) . ')');
    }
}

