<?php

use App\Http\Controllers\Api\JadwalDropdownController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\RuanganController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Modul Data (Pemisahan akses di level controller / router dengan middlewareFor)
    Route::resource('data', DataController::class)
        ->except(['show'])
        ->parameters(['data' => 'datum'])
        ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], ['role:admin']);

    // Master Data (Full Admin Only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('jadwal', JadwalController::class)->except(['show']);
        Route::resource('klaster', \App\Http\Controllers\KlasterController::class)->except(['show']);
        Route::resource('mata-kuliah', MataKuliahController::class)->except(['show']);
        Route::resource('dosen', DosenController::class)->except(['show']);
        Route::resource('ruangan', RuanganController::class)->except(['show']);

        Route::prefix('api/jadwal-dropdown')->name('api.jadwal-dropdown.')->group(function () {
            Route::get('/dosen', [JadwalDropdownController::class, 'dosen'])->name('dosen');
            Route::get('/ruangan', [JadwalDropdownController::class, 'ruangan'])->name('ruangan');
        });
    });
});
