<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaPengirimanController;
use App\Http\Controllers\Admin\KelolaKurirController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\ShipmentController as AdminShipmentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\Kurir\KelolaStatusController as KelolaStatusController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\Kurir\DaftarPengirimanController as DaftarPengirimanController;
use App\Http\Controllers\Kurir\LiveTrackingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------- Guest Route -------------------
Route::get('/', function() { return Auth::check() ? redirect()->route('dashboard') : view('PublicUser.home');
});

// ------------------- Authenticated Customer Routes -------------------
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
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
    Route::get('/shipments/confirmation/{order}', [ShipmentController::class, 'confirmation'])->name('shipments.confirmation');
    
    Route::get('/list', [ShipmentController::class, 'List'])->name('customer.active');
    Route::get('/history', [ShipmentController::class, 'history'])->name('customer.history');

    // Rute untuk Midtrans tetap sama
    Route::get('/payment/finish', [ShipmentController::class, 'paymentFinish'])->name('payment.finish');
    
    Route::patch('/profile/update/{field}', [ProfileController::class, 'updateField'])->name('profile.update-field');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/kurir/scan/{tracking_number}', [ShipmentController::class, 'scanTrack'])->name('kurir.scan.track');
});

// ------------------- Authenticated Admin Roles -------------------
Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard_admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard_admin');
    // ----- Kelola Pengiriman dan Penugasan Kurir -----
    Route::get('/kelola_pengiriman', [AdminShipmentController::class, 'index'])->name('admin.kelola_pengiriman');
    // Rute untuk mengambil kurir berdasarkan area ID
    Route::get('/couriers/by-area/{area_id}', [AdminShipmentController::class, 'getCouriersByArea'])->name('couriers.byArea');
    // Rute untuk memproses form penugasan kurir
    Route::post('/shipments/assign-courier', [AdminShipmentController::class, 'assignCourier'])->name('shipments.assignCourier');
    // Rute API untuk data chart di dashboard
    Route::get('/api/pengiriman-per-wilayah', [DashboardAdminController::class, 'getPengirimanPerWilayah'])->name('admin.api.pengiriman-per-wilayah');
    // Rute untuk Kelola Akun Kurir
    Route::get('/kelola_kurir', [KelolaKurirController::class, 'index'])->name('admin.kelola_kurir');
    Route::get('/tambah_kurir', [KelolaKurirController::class, 'create'])->name('admin.tambah_kurir');
    Route::post('/kelola_kurir', [KelolaKurirController::class, 'store'])->name('admin.kelola_kurir.store');
    Route::get('/kelola_kurir/{id}/edit', [KelolaKurirController::class, 'edit'])->name('admin.kelola_kurir.edit');
    Route::put('/kelola_kurir/{id}', [KelolaKurirController::class, 'update'])->name('admin.kelola_kurir.update');
    Route::delete('/kelola_kurir/{id}', [KelolaKurirController::class, 'destroy'])->name('admin.kelola_kurir.destroy');

    // THIS IS THE CORRECTED ROUTE NAME
    Route::get('/status_pengiriman', [App\Http\Controllers\Admin\ShipmentController::class, 'statusPengiriman'])->name('admin.status_pengiriman');
    Route::get('/history_pengiriman', [App\Http\Controllers\Admin\ShipmentController::class, 'historyPengiriman'])->name('admin.history_pengiriman');
    });

// ------------------- Authenticated Courier Routes -------------------
Route::prefix('courier')->middleware(['auth', 'role:courier'])->group(function () {
    Route::get('/dashboard', fn() => view('kurir.dashboard'))->name('kurir.dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('courier.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('courier.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('courier.profile.destroy');
    Route::post('/update-location', [LiveTrackingController::class, 'updateLocation'])->name('kurir.update_location');
    Route::get('/live-tracking', [LiveTrackingController::class, 'index'])->name('kurir.live_tracking');
    Route::get('/daftar_pengiriman', [DaftarPengirimanController::class, 'index'])->name('kurir.daftar_pengiriman');
    Route::get('/kelola_status', [KelolaStatusController::class, 'Index'])->name('kurir.kelola_status');
    Route::post('/shipment/update-status', [KelolaStatusController::class, 'konfirmasiStatus'])->name('shipment.updateStatus');
    Route::get('/history_pengiriman_kurir', [KelolaStatusController::class, 'history'])->name('kurir.history_pengiriman_kurir');
    Route::get('/resi/{shipmentID}/download', [KelolaStatusController::class, 'downloadResi'])->name('kurir.downloadResi');
    Route::get('/print-resi/{shipmentID}', [KelolaStatusController::class, 'printResi'])->name('kurir.printResi');
}); 


require __DIR__.'/auth.php';

Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

Route::post('/tarif/hitung', [TarifController::class, 'hitungTarif'])->name('tarif.hitung');
Route::get('/courier/live_tracking', [LiveTrackingController::class, 'index'])->name('kurir.live_tracking');
Route::post('/kurir/update_location', [LiveTrackingController::class, 'updateLocation'])->name('kurir.update_location');
Route::get('/api/shipment-location', [LiveTrackingController::class, 'getShipmentLocation'])->name('api.shipment_location');
