<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerifikasiController;

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
