<?php

use App\Http\Controllers\AlokasiController;
use App\Http\Controllers\Api\GroupController as ApiGroupController;
use App\Http\Controllers\Api\SkpdController as ApiSkpdController;
use App\Http\Controllers\Api\TahapanController as ApiTahapanController;
use App\Http\Controllers\Auth\LoginWithGoogleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Master\AkunController;
use App\Http\Controllers\Master\PrioritasController;
use App\Http\Controllers\Master\PrioritasSdController;
use App\Http\Controllers\Master\PrioritasSdSkController;
use App\Http\Controllers\Master\PrioritasSkController;
use App\Http\Controllers\Master\RealisasiController;
use App\Http\Controllers\Master\SipdController;
use App\Http\Controllers\Master\SkpdController;
use App\Http\Controllers\Master\TahapanController;
use App\Http\Controllers\PerbandinganController;
use App\Http\Controllers\RealisasiController as ControllersRealisasiController;
use App\Http\Controllers\Setting\GroupController;
use App\Http\Controllers\Setting\SosmedController;
use App\Http\Controllers\Setting\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LandingController::class, 'index'])->name('landing');

Auth::routes();
Route::get('authorized/google', [LoginWithGoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('authorized/google/callback', [LoginWithGoogleController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard/urusan', [HomeController::class, 'urusan'])->name('urusan');
    Route::get('/dashboard/perbandingan-rekening', [PerbandinganController::class, 'perbandinganByRekening'])->name('perbandingan.rekening');
    Route::get('/dashboard/perbandingan-urusan', [PerbandinganController::class, 'perbandinganUrusan'])->name('perbandingan.urusan');
    
    Route::get('/dashboard/realisasi-rekening', [ControllersRealisasiController::class, 'perbandinganByRekening'])->name('realisasi.rekening');
    Route::get('/dashboard/realisasi-urusan', [ControllersRealisasiController::class, 'perbandinganUrusan'])->name('realisasi.urusan');

    Route::get('/alokasi', [AlokasiController::class, 'index'])->name('alokasi');
    
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('tahapan', TahapanController::class);
        Route::get('/get-tahapan/{id}', [TahapanController::class, 'getTahapan'])->name('get-tahapan');
        
        Route::resource('skpd', SkpdController::class);
        Route::post('skpd/import', [SkpdController::class, 'import'])->name('skpd.import');
        
        Route::get('akun/nilai', [AkunController::class, 'nilai'])->name('akun.nilai');
        Route::get('akun/{kode}/skpd-nilai', [AkunController::class, 'skpd'])->name('akun.nilai.skpd');
        Route::get('akun/{kode_rekening}/skpd-nilai/{kode_skpd}/subkeg-nilai', [AkunController::class, 'subkeg'])->name('akun.nilai.subkeg');
        Route::resource('akun', AkunController::class);
        Route::post('akun/import', [AkunController::class, 'import'])->name('akun.import');
        
        Route::get('/sipd-bandingkan', [SipdController::class, 'bandingkan'])->name('sipd.bandingkan');
        Route::resource('sipd', SipdController::class);

        Route::resource('prioritas', PrioritasController::class);
        Route::post('/prioritas/map/{id}', [PrioritasController::class, 'upsertMap'])->name('prioritas.upsertmap');
        Route::get('/prioritas/report-detail/{id}', [PrioritasController::class, 'report'])->name('prioritas.report');
        Route::get('/prioritas/report-recap/{id}', [PrioritasController::class, 'recap'])->name('prioritas.recap');

        Route::resource('prioritas-sk', PrioritasSkController::class);
        Route::post('/prioritas-sk/map/{id}', [PrioritasSkController::class, 'upsertMap'])->name('prioritas-sk.upsertmap');
        Route::get('/prioritas-sk/report-detail/{id}', [PrioritasSkController::class, 'report'])->name('prioritas-sk.report');
        Route::get('/prioritas-sk/report-recap/{id}', [PrioritasSkController::class, 'recap'])->name('prioritas-sk.recap');

        Route::resource('prioritas-sd', PrioritasSdController::class);
        Route::post('/prioritas-sd/map/{id}', [PrioritasSdController::class, 'upsertMap'])->name('prioritas-sd.upsertmap');
        Route::get('/prioritas-sd/report-detail/{id}', [PrioritasSdController::class, 'report'])->name('prioritas-sd.report');
        Route::post('/prioritas-sd/report-detail/{id}', [PrioritasSdController::class, 'updateNilai'])->name('prioritas-sd.updatenilai');
        Route::get('/prioritas-sd/report-recap/{id}', [PrioritasSdController::class, 'recap'])->name('prioritas-sd.recap');
        
        Route::resource('prioritas-sd-sk', PrioritasSdSkController::class);
        Route::post('/prioritas-sd-sk/map/{id}', [PrioritasSdSkController::class, 'upsertMap'])->name('prioritas-sd-sk.upsertmap');
        Route::get('/prioritas-sd-sk/report-detail/{id}', [PrioritasSdSkController::class, 'report'])->name('prioritas-sd-sk.report');
        Route::post('/prioritas-sd-sk/report-detail/{id}', [PrioritasSdSkController::class, 'updateNilai'])->name('prioritas-sd-sk.updatenilai');
        Route::get('/prioritas-sd-sk/report-recap/{id}', [PrioritasSdSkController::class, 'recap'])->name('prioritas-sd-sk.recap');

        Route::resource('realisasi', RealisasiController::class);
        Route::get('/realisasi-bandingkan', [RealisasiController::class, 'bandingkan'])->name('realisasi.bandingkan');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::resource('pengguna', UserController::class);
        Route::resource('group', GroupController::class);
        Route::resource('sosmed', SosmedController::class);
    });

    /**
     * API Routes
     */
    Route::prefix('api/master')->name('api.master.')->group(function () {
        Route::get('tahapan', [ApiTahapanController::class, 'index'])->name('tahapan.index');
        Route::get('group', [ApiGroupController::class, 'index'])->name('group.index');
        Route::get('group/show/{id}', [ApiGroupController::class, 'show'])->name('group.show');
        Route::get('skpd', [ApiSkpdController::class, 'index'])->name('skpd.index');
    });
});
