<?php

use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CekKelayakanController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda Publik
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Autentikasi Pengguna (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Logout Pengguna
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Alur Onboarding (Wajib setelah registrasi sebelum masuk dasbor)
Route::middleware('auth')->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/', [OnboardingController::class, 'welcome'])->name('welcome');
    Route::get('/role', [OnboardingController::class, 'showRole'])->name('role');
    Route::post('/role', [OnboardingController::class, 'storeRole']);
    Route::get('/profile', [OnboardingController::class, 'showProfile'])->name('profile');
    Route::post('/profile/desa', [OnboardingController::class, 'storeProfileDesa'])->name('profile.desa');
    Route::post('/profile/penyalur', [OnboardingController::class, 'storeProfilePenyalur'])->name('profile.penyalur');
});

// Dasbor Peran Desa (F-01 Data Master Desa)
Route::middleware(['auth', 'onboarding', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('desa.dashboard');
    })->name('dashboard');
});

// Dasbor & Kelola Data BPBD (Admin BPBD)
Route::middleware(['auth', 'onboarding', 'role:bpbd'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('desa', DesaController::class)->only(['index', 'show', 'edit', 'update']);
});

// Dasbor Peran Mitra Penyalur Air
Route::middleware(['auth', 'onboarding', 'role:penyalur'])->prefix('penyalur')->name('penyalur.')->group(function () {
    Route::get('/dashboard', function () {
        return view('penyalur.dashboard');
    })->name('dashboard');
});

// Fitur F-02: Cek Kelayakan Mandiri (AHP - SAW)
Route::get('/cek-kelayakan', [CekKelayakanController::class, 'index'])->name('cek-kelayakan.index');
Route::post('/cek-kelayakan', [CekKelayakanController::class, 'calculate'])->name('cek-kelayakan.calculate');

// Fitur F-03: Pengajuan Bantuan Air Bersih
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
