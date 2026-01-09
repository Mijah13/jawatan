<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\PasswordController;
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
// Dashboard
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Password Change
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'updatePassword'])->name('password.update');


    // Keluarga
    Route::get('/keluarga', [KeluargaController::class, 'index']);
    // Kakitangan
    Route::get('/kakitangan', [KakitanganController::class, 'index']);

    Route::get('/kakitangan/display/{id}', [KakitanganController::class, 'display'])->name('kakitangan.display');
    Route::get('/kakitangan/carian', [KakitanganController::class, 'carian'])->name('kakitangan.carian');
    Route::get('/kakitangan/edit/{id}', [KakitanganController::class, 'edit'])->name('kakitangan.edit');
    Route::put('/kakitangan_update/{id}', [KakitanganController::class, 'update'])->name('kakitangan.update');
    Route::get('/kakitangan/delete/{id}', [KakitanganController::class, 'destroy'])->name('kakitangan.delete');
    Route::get('/kakitangan/reset/{id}', [KakitanganController::class, 'reset'])->name('kakitangan.reset');
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
    Route::get('/surat/edit/{id}', [SuratPengesahanController::class, 'edit'])->name('surat.edit');
    Route::put('/surat/update/{id}', [SuratPengesahanController::class, 'update'])->name('surat.update');
    Route::get('/surat/cetak/{id}', [SuratPengesahanController::class, 'cetak'])->name('surat.cetak');

    // Gaji
    Route::get('/gaji', [GajiController::class, 'index'])->name('gaji.index');
    Route::get('/gaji/tambah', [GajiController::class, 'createGaji'])->name('gaji.create');
    Route::post('/gaji/simpan', [GajiController::class, 'storeGaji'])->name('gaji.store');
    Route::put('/gaji/update/{id}', [GajiController::class, 'updateGaji'])->name('gaji.update');
    Route::get('/gaji/gaji_edit/{id}', [GajiController::class, 'editGaji'])->name('gaji.gaji_edit');

    Route::get('/elaun/tambah', [GajiController::class, 'createElaun'])->name('elaun.create');
    Route::post('/elaun/simpan', [GajiController::class, 'storeElaun'])->name('elaun.store');

    Route::get('/elaun/destroy_elaun/{id}', [GajiController::class, 'destroyElaun'])->name('elaun.destroy_elaun');
    Route::get('/elaun/edit_elaun/{id}', [GajiController::class, 'editElaun'])->name('elaun.edit_elaun');
    Route::put('/elaun/update_elaun/{id}', [GajiController::class, 'updateElaun'])->name('elaun.update_elaun');

    // Taraf Perkhidmatan
    Route::get('/taraf', [TarafPerkhidmatanController::class, 'edit'])->name('taraf.edit');
    Route::post('/taraf/update', [TarafPerkhidmatanController::class, 'update'])->name('taraf.update');

    // Surat Akuan Perubatan
    Route::get('/surat_akuan', [SuratAkuanPerubatanController::class, 'index'])->name('surat_akuan.index');
    Route::get('/surat_akuan/mohon', [SuratAkuanPerubatanController::class, 'create'])->name('surat_akuan.create');
    Route::post('/surat_akuan/simpan', [SuratAkuanPerubatanController::class, 'store'])->name('surat_akuan.store');
    Route::get('/surat_akuan/edit/{id}', [SuratAkuanPerubatanController::class, 'edit'])->name('surat_akuan.edit');
    Route::put('/surat_akuan/update/{id}', [SuratAkuanPerubatanController::class, 'update'])->name('surat_akuan.update');
    Route::get('/surat_akuan/delete/{id}', [SuratAkuanPerubatanController::class, 'destroy'])->name('surat_akuan.destroy');
    Route::get('/surat_akuan/cetak/{id}', [SuratAkuanPerubatanController::class, 'cetak'])->name('surat_akuan.cetak');


    // Latihan
    Route::match(['get', 'post'], '/latihan/senarai', [LatihanController::class, 'senarai'])->name('latihan.senarai');
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
        Route::get('/senarai_isytihar', [LaporanController::class, 'senarai_isytihar'])->name('senarai_isytihar');
        Route::get('/gagal_isytihar', [LaporanController::class, 'gagal_isytihar'])->name('gagal_isytihar');
    });

    // Pentadbir - Penetapan (Settings) Routes
    Route::prefix('pentadbir')->name('pentadbir.')->group(function () {
        Route::match(['get', 'post'], '/peringkat-sumbangan', [PentadbirController::class, 'peringkatSumbangan'])->name('peringkat_sumbangan');
        Route::get('/peringkat-sumbangan/{id}/edit', [PentadbirController::class, 'peringkatSumbanganEdit'])->name('peringkat_sumbangan.edit');
        Route::put('/peringkat-sumbangan/{id}/update', [PentadbirController::class, 'peringkatSumbanganUpdate'])->name('peringkat_sumbangan.update');
        Route::delete('/peringkat-sumbangan/{id}/destroy', [PentadbirController::class, 'peringkatSumbanganDestroy'])->name('peringkat_sumbangan.destroy');

        Route::match(['get', 'post'], '/program', [PentadbirController::class, 'program'])->name('program');
        Route::get('/program/{id}/edit', [PentadbirController::class, 'programEdit'])->name('program.edit');
        Route::put('/program/{id}/update', [PentadbirController::class, 'programUpdate'])->name('program.update');
        Route::delete('/program/{id}/destroy', [PentadbirController::class, 'programDestroy'])->name('program.destroy');

        Route::get('/unit/{id}/edit', [PentadbirController::class, 'unitEdit'])->name('unit.edit');
        Route::put('/unit/{id}/update', [PentadbirController::class, 'unitUpdate'])->name('unit.update');
        Route::delete('/unit/{id}/destroy', [PentadbirController::class, 'unitDestroy'])->name('unit.destroy');
        Route::match(['get', 'post'], '/unit', [PentadbirController::class, 'unit'])->name('unit');

        Route::match(['get', 'post'], '/jenis-isytihar', [PentadbirController::class, 'jenisIsytihar'])->name('jenis_isytihar');
        Route::get('/jenis-isytihar/{id}/edit', [PentadbirController::class, 'jenisIsytiharEdit'])->name('jenis_isytihar.edit');
        Route::put('/jenis-isytihar/{id}/update', [PentadbirController::class, 'jenisIsytiharUpdate'])->name('jenis_isytihar.update');
        Route::delete('/jenis-isytihar/{id}/destroy', [PentadbirController::class, 'jenisIsytiharDestroy'])->name('jenis_isytihar.destroy');


        Route::match(['get', 'post'], '/jenis-penempatan', [PentadbirController::class, 'jenisPenempatan'])->name('jenis_penempatan');
        Route::get('/jenis-penempatan/{id}/edit', [PentadbirController::class, 'jenisPenempatanEdit'])->name('jenis_penempatan.edit');
        Route::put('/jenis-penempatan/{id}/update', [PentadbirController::class, 'jenisPenempatanUpdate'])->name('jenis_penempatan.update');
        Route::delete('/jenis-penempatan/{id}/destroy', [PentadbirController::class, 'jenisPenempatanDestroy'])->name('jenis_penempatan.destroy');


        Route::match(['get', 'post'], '/jawatan', [PentadbirController::class, 'jawatan'])->name('jawatan');
        Route::get('/jawatan/{id}/edit', [PentadbirController::class, 'jawatanEdit'])->name('jawatan.edit');
        Route::put('/jawatan/{id}/update', [PentadbirController::class, 'jawatanUpdate'])->name('jawatan.update');
        Route::delete('/jawatan/{id}/destroy', [PentadbirController::class, 'jawatanDestroy'])->name('jawatan.destroy');


        Route::match(['get', 'post'], '/gred', [PentadbirController::class, 'gred'])->name('gred');
        Route::get('/gred/{id}/edit', [PentadbirController::class, 'gredEdit'])->name('gred.edit');
        Route::put('/gred/{id}/update', [PentadbirController::class, 'gredUpdate'])->name('gred.update');
        Route::delete('/gred/{id}/destroy', [PentadbirController::class, 'gredDestroy'])->name('gred.destroy');


        Route::match(['get', 'post'], '/perjawatan', [PentadbirController::class, 'perjawatan'])->name('perjawatan');
        Route::get('/perjawatan/{id}/edit', [PentadbirController::class, 'perjawatanEdit'])->name('perjawatan.edit');
        Route::put('/perjawatan/{id}/update', [PentadbirController::class, 'perjawatanUpdate'])->name('perjawatan.update');
        Route::delete('/perjawatan/{id}/destroy', [PentadbirController::class, 'perjawatanDestroy'])->name('perjawatan.destroy');

        Route::match(['get', 'post'], '/elaun', [PentadbirController::class, 'elaun'])->name('elaun');
        Route::get('/elaun/{id}/edit', [PentadbirController::class, 'elaunEdit'])->name('elaun.edit');
        Route::put('/elaun/{id}/update', [PentadbirController::class, 'elaunUpdate'])->name('elaun.update');
        Route::delete('/elaun/{id}/destroy', [PentadbirController::class, 'elaunDestroy'])->name('elaun.destroy');

        Route::match(['get', 'post'], '/moto-hari-pekerja', [PentadbirController::class, 'motoHariPekerja'])->name('moto_hari_pekerja');
        Route::get('/moto-hari-pekerja/{id}/edit', [PentadbirController::class, 'motoHariPekerjaEdit'])->name('moto_hari_pekerja.edit');
        Route::put('/moto-hari-pekerja/{id}/update', [PentadbirController::class, 'motoHariPekerjaUpdate'])->name('moto_hari_pekerja.update');
        Route::delete('/moto-hari-pekerja/{id}/destroy', [PentadbirController::class, 'motoHariPekerjaDestroy'])->name('moto_hari_pekerja.destroy');

        // Surat Pengesahan
        Route::match(['get', 'post'], '/surat-pengesahan-cari', [PentadbirController::class, 'suratPengesahanCari'])->name('surat_pengesahan_cari');
        Route::match(['get', 'post'], '/surat-pengesahan-pelulus', [PentadbirController::class, 'suratPengesahanPelulus'])->name('surat_pengesahan_pelulus');

        // Surat Akuan Perubatan
        Route::match(['get', 'post'], '/surat-akuan-senarai', [PentadbirController::class, 'suratAkuanSenarai'])->name('surat_akuan_senarai');
        Route::get('/surat-akuan-senarai/{id}/edit', [PentadbirController::class, 'suratAkuanSenaraiEdit'])->name('surat_akuan_senarai.edit');
        Route::put('/surat-akuan-senarai/{id}/update', [PentadbirController::class, 'suratAkuanSenaraiUpdate'])->name('surat_akuan_senarai.update');
        Route::delete('/surat-akuan-senarai/{id}/destroy', [PentadbirController::class, 'suratAkuanSenaraiDestroy'])->name('surat_akuan_senarai.destroy');

        Route::match(['get', 'post'], '/surat-akuan-pelulus', [PentadbirController::class, 'suratAkuanPelulus'])->name('surat_akuan_pelulus');
        Route::get('/surat-akuan-pelulus/{id}/edit', [PentadbirController::class, 'suratAkuanPelulusEdit'])->name('surat_akuan_pelulus.edit');
        Route::put('/surat-akuan-pelulus/{id}/update', [PentadbirController::class, 'suratAkuanPelulusUpdate'])->name('surat_akuan_pelulus.update');
        Route::delete('/surat-akuan-pelulus/{id}/destroy', [PentadbirController::class, 'suratAkuanPelulusDestroy'])->name('surat_akuan_pelulus.destroy');
    });

    // Route::resource('laporan', LaporanController::class); // Removed as resource methods were removed
    // Route::resource('pentadbir', PentadbirController::class);

    // Bantuan (Help) Routes
    Route::prefix('bantuan')->name('bantuan.')->group(function () {
        Route::get('/tentang', [BantuanController::class, 'tentang'])->name('tentang');
        Route::get('/manual', [BantuanController::class, 'manual'])->name('manual');
        Route::get('/manual-permohonan-surat-pengesahan', [BantuanController::class, 'manualPermohonanSuratPengesahan'])->name('manual_permohonan_surat_pengesahan');
        Route::get('/manual-pelulus-surat-pengesahan', [BantuanController::class, 'manualPelulusSuratPengesahan'])->name('manual_pelulus_surat_pengesahan');
        Route::get('/manual-surat-akuan-perubatan', [BantuanController::class, 'manualSuratAkuanPerubatan'])->name('manual_surat_akuan_perubatan');
        Route::get('/cadangan', [BantuanController::class, 'cadangan'])->name('cadangan');
    });

    Route::resource('bantuan', BantuanController::class);
});

require __DIR__ . '/auth.php';
