<?php

use App\Http\Controllers\CekKelayakanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/cek-kelayakan');
Route::get('/cek-kelayakan', [CekKelayakanController::class, 'index'])
    ->name('cek-kelayakan.index');
Route::post('/cek-kelayakan', [CekKelayakanController::class, 'calculate'])
    ->name('cek-kelayakan.calculate');
