<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourierLoginController;
use App\Http\Controllers\CreateLoginController;
use App\Http\Controllers\DeleteCourierController;
use App\Http\Controllers\EditCourierController;
use App\Http\Controllers\CreateCourierController;
use App\Http\Controllers\KelolaPengirimanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------- Guest Route -------------------
Route::get('/', fn() => view('PublicUser.home'));

// ------------------- Authenticated User Routes -------------------
Route::get('/dashboard', fn() => view('User.dashboard'))         ->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/pengiriman', fn() => view('User.daftar_pengiriman'))->middleware(['auth', 'verified'])->name('pengiriman');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])     ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ------------------- Kurir Auth Routes -------------------
Route::get('/auth/kurir/masuk', [CourierLoginController::class, 'showLoginForm']) ->name('kurir.login');
Route::post('/auth/kurir/masuk', [CourierLoginController::class, 'login']);
Route::post('/kurir/logout', [CourierLoginController::class, 'logout'])           ->name('kurir.logout');
Route::get('/kurir/dashboard', fn() => view('kurir.dashboard'))                   ->name('dashboard.kurir');

// ------------------- Admin Auth Routes -------------------
Route::get('/admin/login', fn() => view('auth.admin.masuk'))                      ->name('admin.login');
Route::get('/login/user', fn() => view('auth.user.masuk'))                        ->name('user.login');

// ------------------- Admin - Kelola Kurir -------------------
Route::get('/admin/kelola_kurir', [CreateCourierController::class, 'index'])      ->name('admin.kelola_kurir');
Route::get('/admin/tambah_kurir', [CreateCourierController::class, 'create'])     ->name('admin.tambah_kurir');
Route::post('/admin/simpan_kurir', [CreateCourierController::class, 'store'])     ->name('admin.simpan_kurir');
Route::delete('/admin/kurir/{id}', DeleteCourierController::class)                ->name('admin.hapus_kurir');
Route::get('/admin/edit_kurir/{id}', [EditCourierController::class, 'editKurir']) ->name('admin.edit_kurir');
Route::put('/admin/update_kurir/{id}', [EditCourierController::class, 'updateKurir'])->name('admin.update_kurir');

// ------------------- Admin - Halaman -------------------
Route::get('/admin/status_pengiriman', fn() => view('admin.status_pengiriman'))   ->name('admin.status_pengiriman');
Route::get('/admin/kelola_pengiriman', fn() => view('admin.kelola_pengiriman'))   ->name('admin.kelola_pengiriman');
Route::get('/admin/history_pengiriman', fn() => view('admin.history_pengiriman')) ->name('admin.history_pengiriman');
Route::get('/admin/dashboard_admin', fn() => view('admin.dashboard_admin'))       ->name('admin.dashboard_admin');

// ------------------- Kurir by Wilayah -------------------
Route::get('/kurir/by-wilayah/{wilayah}', [KelolaPengirimanController::class, 'getKurirByWilayah'])->name('kurir.byWilayah');
Route::post('admin/kelola_pengiriman', [KelolaPengirimanController::class, 'store']);
