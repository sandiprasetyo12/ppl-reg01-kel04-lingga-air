<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\CekKelayakanController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\VerifikasiController;

// Route Public
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'bpbd' => redirect()->route('verifikasi.index'),
            'penyalur' => redirect()->route('penyalur.dashboard'),
            default => redirect()->route('desa.dashboard'),
        };
    }
    return view('welcome');
})->name('home');

// Fitur F-04: Verifikasi Pengajuan (BPBD Purbalingga)
Route::middleware(['auth', 'role:bpbd'])->prefix('verifikasi')->name('verifikasi.')->group(function () {
    // PBI-010: Antrean pengajuan terurut skor AHP-SAW
    Route::get('/', [VerifikasiController::class, 'index'])->name('index');
    // PBI-010: Detail lengkap data permohonan
    Route::get('/{id}', [VerifikasiController::class, 'show'])->name('show');
    // PBI-011 & PBI-012: Penetapan Keputusan (Disetujui, Ditolak, Revisi) + Audit Trail Log
    Route::post('/{id}/keputusan', [VerifikasiController::class, 'prosesKeputusan'])->name('keputusan');
});

// Route Auth (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route Onboarding (Sudah Login tapi belum selesai onboarding)
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    // step 1: welcome
    Route::get('/', [OnboardingController::class, 'welcome'])->name('welcome');

    // step 2: pilih role
    Route::get('/role', [OnboardingController::class, 'showRole'])->name('role');
    Route::post('/role', [OnboardingController::class, 'storeRole']);

    // step 3: isi profil
    Route::get('/profile', [OnboardingController::class, 'showProfile'])->name('profile');
    Route::post('/profile/desa', [OnboardingController::class, 'storeProfileDesa'])->name('profile.desa');
    Route::post('/profile/penyalur', [OnboardingController::class, 'storeProfilePenyalur'])->name('profile.penyalur');
});

// Route Dashboard & Fitur Peran Desa (F-01, F-02, & F-03)
Route::middleware(['auth', 'onboarding', 'role:desa'])->group(function () {
    Route::prefix('desa')->name('desa.')->group(function () {
        Route::get('/dashboard', function () {
            return view('desa.dashboard');
        })->name('dashboard');
    });

    // Fitur F-02: Cek Kelayakan Mandiri (AHP - SAW)
    Route::get('/cek-kelayakan', [CekKelayakanController::class, 'index'])->name('cek-kelayakan.index');
    Route::post('/cek-kelayakan', [CekKelayakanController::class, 'calculate'])->name('cek-kelayakan.calculate');

    // Fitur F-03: Pengajuan Bantuan Air Bersih Desa
    Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}/berhasil', [PengajuanController::class, 'success'])->name('pengajuan.success');
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::get('/pengajuan/{pengajuan}/edit', [PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{pengajuan}', [PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::post('/pengajuan/{pengajuan}/kirim', [PengajuanController::class, 'submit'])->name('pengajuan.submit');
    Route::delete('/berkas/{berkas}', [PengajuanController::class, 'destroyBerkas'])->name('berkas.destroy');
});

// Route Dashboard & Kelola Data BPBD (Admin BPBD)
Route::middleware(['auth', 'onboarding', 'role:bpbd'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('desa', DesaController::class)->only(['index', 'show', 'edit', 'update']);
});

// Route Dashboard Mitra Penyalur
Route::middleware(['auth', 'onboarding', 'role:penyalur'])->prefix('penyalur')->name('penyalur.')->group(function () {
    Route::get('/dashboard', function () {
        return view('penyalur.dashboard');
    })->name('dashboard');
});