<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\LaporanHarianController;
use App\Http\Controllers\PengabdianController;
use App\Http\Controllers\MitraPengabdianController;
use App\Http\Controllers\PembimbingController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Models\Presensi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::group(['prefix' => 'components', 'as' => 'components.'], function () {
        Route::get('/alert', function () {
            return view('admin.component.alert');
        })->name('alert');
        Route::get('/accordion', function () {
            return view('admin.component.accordion');
        })->name('accordion');
    });


    Route::middleware([ 'guru'])->group(function () {
        Route::get('mitra/{mitra}/pembimbing/assign', [PembimbingController::class, 'assign'])->name('pembimbing.assign');
        Route::post('mitra/{mitra}/pembimbing/assign', [PembimbingController::class, 'storeAssign'])->name('pembimbing.storeAssign');
        Route::delete('mitra/{mitra}/pembimbing/{pembimbing}/detach', [PembimbingController::class, 'detach'])->name('pembimbing.detach');
        
        Route::get('pengabdian/pembimbing/assign', [PengabdianController::class, 'pembimbingAssign'])->name('pengabdian.pembimbing.assign');
        // Route::post('pengabdian/pembimbing/assign', [PengabdianController::class, 'pembimbingStoreAssign'])->name('pengabdian.pembimbing.storeAssign');
        Route::post('pengabdian/pembimbing/{pembimbing}/siswa/', [PengabdianController::class, 'siswaAssign'])->name('pengabdian.pembimbing.siswa.assign');
        Route::post('pengabdian/pembimbing/{pembimbing}/siswa/{siswa}', [PengabdianController::class, 'storeSiswaAssign'])->name('pengabdian.pembimbing.siswa.storeAssign');
    });


    Route::resource('users', UserController::class);

    Route::resource('pengabdian', PengabdianController::class);

    Route::post('/penilaian/predict/{pengabdian_id}',[PenilaianController::class,'predict'])->name('penilaian.predict');
    Route::resource('penilaian', PenilaianController::class);

    Route::resource('laporan', LaporanHarianController::class);
    
    Route::put('/presensi/{id}', [PresensiController::class, 'updateState'])->name('presensi.update');

    // Route::resource('presensi', PresensiController::class);


    Route::get('/mitra/perusahaan', [MitraPengabdianController::class, 'showMyMitra'])->name('perusahaan');
    Route::resource('mitra', MitraPengabdianController::class);
    Route::get('/users/pengguna/{user_id}', [UserController::class, 'resetRole'])->name('users.reset');

    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/pengguna', [SiswaController::class, 'pengguna'])->name('siswa.pengguna');
    Route::get('/siswa/create/{user_id}', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa/store/{user_id}', [SiswaController::class, 'store'])->name('siswa.store');

    Route::get('/pembimbing', [PembimbingController::class, 'index'])->name('pembimbing.index');
    Route::get('/pembimbing/pengguna', [PembimbingController::class, 'pengguna'])->name('pembimbing.pengguna');
    Route::get('/pembimbing/create/{user_id}', [PembimbingController::class, 'create'])->name('pembimbing.create');
    Route::post('/pembimbing/store/{user_id}', [PembimbingController::class, 'store'])->name('pembimbing.store');

    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::get('/guru/pengguna', [GuruController::class, 'pengguna'])->name('guru.pengguna');
    Route::get('/guru/create/{user_id}', [GuruController::class, 'create'])->name('guru.create');
    Route::post('/guru/store/{user_id}', [GuruController::class, 'store'])->name('guru.store');


    // Route::post('/penilaian/{pengabdian_id}',[GuruController::class,'store'])->name('penilaian.store');

});
