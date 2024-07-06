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
use App\Http\Controllers\PermintaanController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\JenisMaterialController;
use App\Http\Controllers\SaldoMaterialController;
use App\Http\Controllers\FungsiMaterialController;
use App\Http\Controllers\JurnalProduksiController;
use App\Http\Controllers\PengeluaranGudangController;
use App\Http\Controllers\PemasukanGudangController;

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
Route::resource('/permintaan', PermintaanController::class)->middleware(['auth']);
Route::get('permintaan/api/{id}', [PermintaanController::class, 'api'])->name('permintaan.api');
Route::get('permintaan/tutup/{id}', [PermintaanController::class, 'tutup'])->name('permintaan.tutup')->middleware(['auth']);
Route::resource('/pengeluaran_gudang', PengeluaranGudangController::class)->middleware(['auth']);
Route::resource('/pemasukan_gudang', PemasukanGudangController::class)->middleware(['auth']);
Route::resource('/jenis_material', JenisMaterialController::class)->middleware(['auth']);
Route::resource('/fungsi_material', FungsiMaterialController::class)->middleware(['auth']);
Route::resource('/material', MaterialController::class)->middleware(['auth']);
Route::resource('/kebutuhan', KebutuhanController::class)->middleware(['auth']);
Route::resource('/jurnal_produksi', JurnalProduksiController::class)->middleware(['auth']);
Route::get('/saldo_material', [SaldoMaterialController::class, 'index'])->name('saldo_material.index')->middleware(['auth']);
Route::post('/saldo_material', [SaldoMaterialController::class, 'proses'])->name('saldo_material.proses')->middleware(['auth']);
Route::get('/saldo_material/{id}', [SaldoMaterialController::class, 'penyesuaian'])->name('saldo_material.penyesuaian')->middleware(['auth']);
