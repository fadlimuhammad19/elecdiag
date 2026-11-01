<?php

use App\Http\Controllers\Admin\AturanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GejalaController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KerusakanController;
use App\Http\Controllers\Admin\KomponenController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\RekomendasiController;
use App\Http\Controllers\Admin\RiwayatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'landing')->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('konsultasi/{komponen}/kamera', [\App\Http\Controllers\KameraController::class, 'analisis'])->name('diagnosa.kamera');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/konsultasi', [DiagnosaController::class, 'index'])->name('diagnosa.index');
    Route::get('/konsultasi/{komponen}', [DiagnosaController::class, 'wizard'])->name('diagnosa.wizard');
    Route::post('/diagnosa', [DiagnosaController::class, 'proses'])->name('diagnosa.proses');
    Route::get('/diagnosa/{diagnosa}', [DiagnosaController::class, 'hasil'])->name('diagnosa.hasil');
    Route::get('/diagnosa/{diagnosa}/cetak', [DiagnosaController::class, 'cetak'])->name('diagnosa.cetak');
    Route::get('/riwayat', [DiagnosaController::class, 'riwayat'])->name('riwayat');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::get('riwayat/ekspor', [RiwayatController::class, 'ekspor'])->name('riwayat.ekspor');

    Route::resource('pengguna', PenggunaController::class)
        ->only(['index', 'update', 'destroy'])
        ->parameters(['pengguna' => 'pengguna']);

    Route::resource('gejala', GejalaController::class)
        ->except('show')
        ->parameters(['gejala' => 'gejala']);

    Route::resource('kerusakan', KerusakanController::class)
        ->except('show')
        ->parameters(['kerusakan' => 'kerusakan']);

    Route::resource('aturan', AturanController::class)
        ->except('show')
        ->parameters(['aturan' => 'aturan']);

    Route::resource('kategori', KategoriController::class)
        ->except('show')
        ->parameters(['kategori' => 'kategori']);

    Route::resource('komponen', KomponenController::class)
        ->except('show')
        ->parameters(['komponen' => 'komponen']);

    Route::resource('rekomendasi', RekomendasiController::class)
        ->except('show')
        ->parameters(['rekomendasi' => 'rekomendasi']);
});