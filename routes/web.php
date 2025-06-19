<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourierLoginController;
use App\Http\Controllers\CreateCourierController;
use App\Http\Controllers\DeleteCourierController;
use App\Http\Controllers\EditCourierController;
use App\Http\Controllers\KelolaPengirimanController;
use App\Http\Controllers\KelolaKurirController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ShipmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------- Guest Route -------------------
Route::get('/', function() { return Auth::check() ? redirect()->route('dashboard') : view('PublicUser.home');
});

// ------------------- Authenticated User Routes -------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('User.dashboard'))->name('dashboard');
   // Langkah 1: Menampilkan form utama untuk mengisi detail pengiriman
    Route::get('/shipments/create-step-1', [ShipmentController::class, 'createStep1'])->name('shipments.create.step1');
    // Memproses data dari Langkah 1
    Route::post('/shipments/store-step-1', [ShipmentController::class, 'storeStep1'])->name('shipments.store.step1');
    
    // Langkah 2: Menampilkan halaman ringkasan & pembayaran
    Route::get('/shipments/create-step-2', [ShipmentController::class, 'createStep2'])->name('shipments.create.step2');
    // Memproses data dari Langkah 2 (Final)
    Route::post('/shipments/store-final', [ShipmentController::class, 'storeFinal'])->name('shipments.store.final');

    // Halaman konfirmasi setelah berhasil
    Route::get('/shipments/confirmation/{orderID}', [ShipmentController::class, 'confirmation'])->name('shipments.confirmation');
    
    // Halaman riwayat pengiriman
    Route::get('/shipments/history', [ShipmentController::class, 'history'])->name('shipments.history');

    // Rute untuk Midtrans tetap sama
    Route::get('/payment/finish', [ShipmentController::class, 'paymentFinish'])->name('payment.finish');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kurir/scan/{tracking_number}', [ShipmentController::class, 'scanTrack'])->name('kurir.scan.track');
});

require __DIR__.'/auth.php';

// ------------------- Kurir Auth Routes -------------------
Route::get('/auth/kurir/masuk', [CourierLoginController::class, 'showLoginForm'])->name('kurir.login');
Route::post('/auth/kurir/masuk', [CourierLoginController::class, 'login']);
Route::post('/kurir/logout', [CourierLoginController::class, 'logout'])->name('kurir.logout');
Route::get('/kurir/dashboard', fn() => view('kurir.dashboard'))->name('dashboard.kurir');

// ------------------- Admin Auth Routes -------------------
Route::get('/admin/login', fn() => view('auth.admin.masuk'))->name('admin.login');
Route::get('/login/user', fn() => view('auth.user.masuk'))->name('user.login');

// ------------------- Admin - Kelola Kurir -------------------
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/kelola_kurir', [KelolaKurirController::class, 'index'])->name('admin.kelola_kurir');
    Route::get('/tambah_kurir', [CreateCourierController::class, 'create'])->name('admin.tambah_kurir');
    Route::post('/simpan_kurir', [CreateCourierController::class, 'store'])->name('admin.simpan_kurir');
    Route::delete('/kurir/{id}', [DeleteCourierController::class, 'destroy'])->name('admin.hapus_kurir');
    Route::get('/edit_kurir/{id}', [EditCourierController::class, 'editKurir'])->name('admin.edit_kurir');
    Route::put('/update_kurir/{id}', [EditCourierController::class, 'updateKurir'])->name('admin.update_kurir');

    // ------------------- Halaman Admin -------------------
    // Remove the redundant route that only returns a view
    // Route::get('/status_pengiriman', fn() => view('admin.status_pengiriman'))->name('admin.status_pengiriman');

  
    Route::get('/dashboard_admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard_admin');
    Route::get('/api/pengiriman-per-wilayah', [DashboardAdminController::class, 'getPengirimanPerWilayah']);

    // ----- Kelola Pengiriman dan Penugasan Kurir -----
    Route::get('/kelola_pengiriman', [KelolaPengirimanController::class, 'index'])->name('admin.kelola_pengiriman');

    // API Routes untuk AJAX
    Route::get('/api/kurir-by-wilayah/{wilayah}', [KelolaPengirimanController::class, 'getKurirByWilayah'])->name('kurir.byWilayah');
    Route::post('/api/assign-kurir', [KelolaPengirimanController::class, 'assignKurir'])->name('assign.kurir');
    Route::get('/kurir-by-username', [KelolaPengirimanController::class, 'getCourierByUsername'])->name('kurir.byUsername');

    // THIS IS THE CORRECTED ROUTE NAME
    Route::get('/status_pengiriman', [KelolaPengirimanController::class, 'statusPengiriman'])->name('admin.status_pengiriman');
    Route::get('/history_pengiriman', [KelolaPengirimanController::class, 'historyPengiriman'])->name('admin.history_pengiriman');
});

Route::get('/kurir/daftar_pengiriman', [KelolaPengirimanController::class, 'daftarPengirimanKurir'])->name('kurir.daftar_pengiriman');
Route::get('/kurir/kelola_status', [KelolaPengirimanController::class, 'updateStatus'])->name('kurir.kelola_status');

