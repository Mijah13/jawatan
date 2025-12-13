<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// New controllers
use App\Http\Controllers\KeluargaController;
use App\Http\Controllers\KakitanganController;
// use App\Http\Controllers\JawatanController;
// use App\Http\Controllers\ElaunController;
use App\Http\Controllers\HartaController;
use App\Http\Controllers\ApcController;
use App\Http\Controllers\PencapaianController;
use App\Http\Controllers\PingatController;
use App\Http\Controllers\SuratPengesahanController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\TarafPerkhidmatanController;
use App\Http\Controllers\SuratAkuanPerubatanController;
use App\Http\Controllers\LatihanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PentadbirController;
use App\Http\Controllers\BantuanController;

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

    Route::get('/kakitangan/display/{id}', [KakitanganController::class, 'display'])->name('kakitangan.display');
    Route::get('/kakitangan/carian', [KakitanganController::class, 'carian'])->name('kakitangan.carian');
    Route::get('/kakitangan/edit/{id}', [KakitanganController::class, 'edit'])->name('kakitangan.edit');
    Route::put('/kakitangan_update/{id}', [KakitanganController::class, 'update'])->name('kakitangan.update');
    Route::get('/kakitangan/delete/{id}', [KakitanganController::class, 'destroy'])->name('kakitangan.delete');
    // Jawatan
    // Route::get('/jawatan', [JawatanController::class, 'index']);

    // Harta
    Route::get('/harta/tambah/{id}', [HartaController::class, 'create'])->name('harta.create');
    Route::post('/harta/simpan', [HartaController::class, 'store'])->name('harta.store');

    // APC
    Route::get('/apc/tambah/{id}', [ApcController::class, 'create'])->name('apc.create');
    Route::post('/apc/simpan', [ApcController::class, 'store'])->name('apc.store');

    // Pencapaian
    Route::get('/pencapaian/tambah/{id}', [PencapaianController::class, 'create'])->name('pencapaian.create');
    Route::post('/pencapaian/simpan', [PencapaianController::class, 'store'])->name('pencapaian.store');

    // Pingat
    Route::get('/pingat/tambah/{id}', [PingatController::class, 'create'])->name('pingat.create');
    Route::post('/pingat/simpan', [PingatController::class, 'store'])->name('pingat.store');

    // Surat Pengesahan
    Route::get('/surat', [SuratPengesahanController::class, 'index'])->name('surat.index');
    Route::get('/surat/mohon', [SuratPengesahanController::class, 'create'])->name('surat.create');
    Route::post('/surat/simpan', [SuratPengesahanController::class, 'store'])->name('surat.store');

    // Gaji
    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::get('/gaji/tambah', [GajiController::class, 'createGaji'])->name('gaji.create');
    Route::post('/gaji/simpan', [GajiController::class, 'storeGaji'])->name('gaji.store');
    Route::get('/elaun/tambah', [GajiController::class, 'createElaun'])->name('elaun.create');
    Route::post('/elaun/simpan', [GajiController::class, 'storeElaun'])->name('elaun.store');

    // Taraf Perkhidmatan
    Route::get('/taraf', [TarafPerkhidmatanController::class, 'edit'])->name('taraf.edit');
    Route::post('/taraf/update', [TarafPerkhidmatanController::class, 'update'])->name('taraf.update');

    // Surat Akuan Perubatan
    Route::get('/surat_akuan', [SuratAkuanPerubatanController::class, 'index'])->name('surat_akuan.index');
    Route::get('/surat_akuan/mohon', [SuratAkuanPerubatanController::class, 'create'])->name('surat_akuan.create');
    Route::post('/surat_akuan/simpan', [SuratAkuanPerubatanController::class, 'store'])->name('surat_akuan.store');

    // Latihan
    Route::resource('latihan', LatihanController::class);

    // =================================================
    // eJAWATAN MODULES ROUTES
    // =================================================
    Route::resource('keluarga', KeluargaController::class);
    Route::resource('kakitangan', KakitanganController::class);
    // Route::resource('jawatan', JawatanController::class);
    // Route::resource('elaun', ElaunController::class);
    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/pengisian_gred', [LaporanController::class, 'pengisian_gred'])->name('pengisian_gred');
        Route::get('/teknikal', [LaporanController::class, 'teknikal'])->name('teknikal');
        Route::get('/lantikan', [LaporanController::class, 'lantikan'])->name('lantikan');
        Route::get('/senarai_perjawatan', [LaporanController::class, 'senarai_perjawatan'])->name('senarai_perjawatan');
        Route::get('/penempatan', [LaporanController::class, 'penempatan'])->name('penempatan');
        Route::get('/luar_ciast', [LaporanController::class, 'luar_ciast'])->name('luar_ciast');
        Route::get('/sambilan', [LaporanController::class, 'sambilan'])->name('sambilan');
        Route::get('/statistik', [LaporanController::class, 'statistik'])->name('statistik');
        Route::get('/bersara', [LaporanController::class, 'bersara'])->name('bersara');
        Route::get('/baru', [LaporanController::class, 'baru'])->name('baru');
        Route::get('/bertukar', [LaporanController::class, 'bertukar'])->name('bertukar');
        Route::get('/apc_pingat', [LaporanController::class, 'apc_pingat'])->name('apc_pingat');
    });

    // Route::resource('laporan', LaporanController::class); // Removed as resource methods were removed
    Route::resource('pentadbir', PentadbirController::class);
    Route::resource('bantuan', BantuanController::class);
});

require __DIR__ . '/auth.php';
