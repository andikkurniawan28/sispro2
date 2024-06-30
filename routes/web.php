<?php

use App\Models\JenisTransaksi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KebutuhanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\JurnalGudangController;
use App\Http\Controllers\JenisMaterialController;
use App\Http\Controllers\FungsiMaterialController;
use App\Http\Controllers\JenisJurnalGudangController;
use App\Http\Controllers\PermintaanController;

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

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('loginProcess');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', DashboardController::class)->name('dashboard')->middleware(['auth']);
Route::get('/setup', [SetupController::class, 'index'])->name('setup.index')->middleware(['auth']);
Route::post('/setup', [SetupController::class, 'process'])->name('setup.process')->middleware(['auth']);
Route::resource('/divisi', DivisiController::class)->middleware(['auth']);
Route::resource('/jabatan', JabatanController::class)->middleware(['auth']);
Route::resource('/user', UserController::class)->middleware(['auth']);
Route::get('/log_aktifitas', ActivityLogController::class)->name('log_aktifitas')->middleware(['auth']);
Route::resource('/satuan', SatuanController::class)->middleware(['auth']);
Route::resource('/gudang', GudangController::class)->middleware(['auth']);
Route::resource('/jenis_jurnal_gudang', JenisJurnalGudangController::class)->middleware(['auth']);
Route::resource('/permintaan', PermintaanController::class)->middleware(['auth']);
Route::resource('/jurnal_gudang', JurnalGudangController::class)->middleware(['auth']);
Route::resource('/jenis_material', JenisMaterialController::class)->middleware(['auth']);
Route::resource('/fungsi_material', FungsiMaterialController::class)->middleware(['auth']);
Route::resource('/material', MaterialController::class)->middleware(['auth']);
Route::resource('/kebutuhan', KebutuhanController::class)->middleware(['auth']);
