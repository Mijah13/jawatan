<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// New controllers
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\KakitanganController;
use App\Http\Controllers\JawatanController;
use App\Http\Controllers\ElaunController;

Route::get('/', function () {
    return redirect()->route('dashboard'); // redirect to dashboard
});

// Dashboard
Route::get('/dashboard', function () {
    // Get the authenticated user from session
    $user = auth()->user();
    return view('dashboard', ['user' => $user]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Keluarga
    Route::get('/keluarga', [KeluargaController::class, 'index']);
    // Kakitangan
    Route::get('/kakitangan', [KakitanganController::class, 'index']);
    // Jawatan
    Route::get('/jawatan', [JawatanController::class, 'index']);

    // =================================================
    // eJAWATAN MODULES ROUTES
    // =================================================
    Route::resource('keluarga', KeluargaController::class);
    Route::resource('kakitangan', KakitanganController::class);
    Route::resource('jawatan', JawatanController::class);
    Route::resource('elaun', ElaunController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('pentadbir', PentadbirController::class);
    Route::resource('bantuan', BantuanController::class);
});

require __DIR__ . '/auth.php';
