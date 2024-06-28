<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;

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
