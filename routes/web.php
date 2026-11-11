<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\Admin\DesaController;

//Route Public
Route::get('/', function () {
    return redirect()->route('verifikasi.index');
});

// Fitur F-04: Verifikasi Pengajuan (BPBD Purbalingga)
Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
    // PBI-010: Antrean pengajuan terurut skor AHP-SAW
    Route::get('/', [VerifikasiController::class, 'index'])->name('index');
    // PBI-010: Detail lengkap data permohonan
    Route::get('/{id}', [VerifikasiController::class, 'show'])->name('show');
    // PBI-011 & PBI-012: Penetapan Keputusan (Disetujui, Ditolak, Revisi) + Audit Trail Log
    Route::post('/{id}/keputusan', [VerifikasiController::class, 'prosesKeputusan'])->name('keputusan');
});

//Route Auth
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
    //step 1: welcome
    Route::get('/', [OnboardingController::class, 'welcome'])->name('welcome');

    //step 2: pilih role
    Route::get('/role', [OnboardingController::class, 'showRole'])->name('role');
    Route::post('/role', [OnboardingController::class, 'storeRole']);

    //step 3: isi profil
    Route::get('/profile', [OnboardingController::class, 'showProfile'])->name('profile');
    Route::post('/profile/desa', [OnboardingController::class, 'storeProfileDesa'])->name('profile.desa');
    Route::post('/profile/penyalur', [OnboardingController::class, 'storeProfilePenyalur'])->name('profile.penyalur');
});

// Route Dashboard (Sudah Login dan sudah selesai onboarding)

// Route untuk role Desa
Route::middleware(['auth', 'onboarding', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('desa.dashboard');
    })->name('dashboard');
});

// Route untuk role BPBD
Route::middleware(['auth', 'onboarding', 'role:bpbd'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Route untuk mengelola data desa (PBI-003)
    Route::resource('desa', DesaController::class)->only(['index', 'show', 'edit', 'update']);
});

// Route untuk role Penyalur
Route::middleware(['auth', 'onboarding', 'role:penyalur'])->prefix('penyalur')->name('penyalur.')->group(function () {
    Route::get('/dashboard', function () {
        return view('penyalur.dashboard');
    })->name('dashboard');
});