<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Autentikasi
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/switch/{id}', [AuthController::class, 'switchUser'])->name('auth.switch');

// Fitur F-03: Pengajuan Bantuan Air Bersih (PBI-007, PBI-008, PBI-009)
Route::middleware(['auth'])->group(function () {
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
