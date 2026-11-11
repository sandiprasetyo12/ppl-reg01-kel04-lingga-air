<?php

use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CekKelayakanController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

// Route Public
Route::get('/', function () {
    return view('welcome');
});

// Route Auth
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

// Route Dashboard (Sudah Login dan sudah selesai onboarding)

// Route untuk role Desa
Route::middleware(['auth', 'onboarding', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    Route::get('/dashboard', function () {
        return view('desa.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'onboarding', 'role:desa'])->group(function () {
    Route::get('/cek-kelayakan', [CekKelayakanController::class, 'index'])
        ->name('cek-kelayakan.index');
    Route::post('/cek-kelayakan', [CekKelayakanController::class, 'calculate'])
        ->name('cek-kelayakan.calculate');
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
