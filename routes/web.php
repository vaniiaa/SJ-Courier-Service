<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KelolaPengirimanController;
use App\Http\Controllers\Admin\KelolaKurirController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\ShipmentController as AdminShipmentController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\Kurir\KelolaStatusController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\Kurir\DaftarPengirimanController;
use App\Http\Controllers\LiveTrackingController; // Moved to main namespace

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------- Guest Route -------------------
Route::get('/', function() { 
    return Auth::check() ? redirect()->route('dashboard') : view('PublicUser.home');
});

// ------------------- Public Live Tracking (Guest Access) -------------------
Route::get('/live-tracking', [LiveTrackingController::class, 'publicTracking'])->name('public.live_tracking');
Route::get('/api/shipment-location', [LiveTrackingController::class, 'getShipmentLocation'])->name('api.shipment_location');

// ------------------- Authenticated Customer Routes -------------------
Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
    Route::get('/dashboard', fn() => view('User.dashboard'))->name('dashboard');
    
    // Shipment creation steps
    Route::get('/shipments/form-pengiriman', [ShipmentController::class, 'formShipment'])->name('user.form_pengiriman');
    Route::post('/shipments/form-pengiriman', [ShipmentController::class, 'storeShipment'])->name('user.store_pengiriman');
    Route::get('/shipments/ringkasan-pengiriman', [ShipmentController::class, 'summaryShipment'])->name('user.ringkasan_pengiriman');
    Route::post('/shipments/store-final', [ShipmentController::class, 'storeFinal'])->name('user.store.final');
    Route::get('/shipments/confirmation/{order}', [ShipmentController::class, 'confirmation'])->name('user.confirmation');
    
    // Customer shipment management
    Route::get('/daftar-pengiriman', [ShipmentController::class, 'List'])->name('user.daftar_pengiriman');
    Route::get('/history', [ShipmentController::class, 'history'])->name('user.history');
    
    // Customer Live Tracking
    Route::get('/live-tracking', [LiveTrackingController::class, 'index'])->name('user.live_tracking');
    Route::get('/api/my-shipments', [LiveTrackingController::class, 'getCustomerShipments'])->name('api.customer_shipments');

    // Payment and profile
    Route::get('/payment/finish', [ShipmentController::class, 'paymentFinish'])->name('payment.finish');
    Route::patch('/profile/update/{field}', [ProfileController::class, 'updateField'])->name('profile.update-field');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/daftar-pengiriman/{shipment}/cancel', [ShipmentController::class, 'cancel'])->name('user.shipment.cancel');
    Route::get('/kurir/scan/{tracking_number}', [ShipmentController::class, 'scanTrack'])->name('kurir.scan.track');
});

// ------------------- Authenticated Admin Routes -------------------
Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard_admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard_admin');
    
    // Admin Live Tracking
    Route::get('/live_tracking_admin', [LiveTrackingController::class, 'index'])->name('admin.live_tracking');
    Route::get('/api/all-shipments', [LiveTrackingController::class, 'getAllActiveShipments'])->name('api.all_shipments');
    
    // Shipment management
    Route::get('/kelola_pengiriman', [AdminShipmentController::class, 'index'])->name('admin.kelola_pengiriman');
    Route::get('/couriers/by-area/{area_id}', [AdminShipmentController::class, 'getCouriersByArea'])->name('couriers.byArea');
    Route::post('/shipments/assign-courier', [AdminShipmentController::class, 'assignCourier'])->name('shipments.assignCourier');
    Route::get('/api/pengiriman-per-wilayah', [DashboardAdminController::class, 'getPengirimanPerWilayah'])->name('admin.api.pengirimanPerWilayah');
    
    // Courier management
    Route::get('/kelola_kurir', [KelolaKurirController::class, 'index'])->name('admin.kelola_kurir');
    Route::get('/tambah_kurir', [KelolaKurirController::class, 'create'])->name('admin.tambah_kurir');
    Route::post('/kelola_kurir', [KelolaKurirController::class, 'store'])->name('admin.kelola_kurir.store');
    Route::get('/kelola_kurir/{id}/edit', [KelolaKurirController::class, 'edit'])->name('admin.kelola_kurir.edit');
    Route::put('/kelola_kurir/{id}', [KelolaKurirController::class, 'update'])->name('admin.kelola_kurir.update');
    Route::delete('/kelola_kurir/{id}', [KelolaKurirController::class, 'destroy'])->name('admin.kelola_kurir.destroy');

    // Shipment status and history
    Route::get('/status_pengiriman', [AdminShipmentController::class, 'statusPengiriman'])->name('admin.status_pengiriman');
    Route::get('/history_pengiriman', [AdminShipmentController::class, 'historyPengiriman'])->name('admin.history_pengiriman');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('admin.profile.destroy');
});

// ------------------- Authenticated Courier Routes -------------------
Route::prefix('courier')->middleware(['auth', 'role:courier'])->group(function () {
    Route::get('/dashboard', fn() => view('kurir.dashboard'))->name('kurir.dashboard');
    
    // Courier Live Tracking
    Route::get('/live-tracking', [LiveTrackingController::class, 'index'])->name('kurir.live_tracking');
    Route::post('/update-location', [LiveTrackingController::class, 'updateLocation'])->name('kurir.update_location');
    Route::get('/api/my-deliveries', [LiveTrackingController::class, 'getCourierShipments'])->name('api.courier_shipments');
    Route::get('/api/active-shipments', [LiveTrackingController::class, 'getActiveCourierShipments'])->name('kurir.api.active_shipments');
    
    // Courier management
    Route::get('/daftar_pengiriman', [DaftarPengirimanController::class, 'index'])->name('kurir.daftar_pengiriman');
    Route::get('/kelola_status', [KelolaStatusController::class, 'Index'])->name('kurir.kelola_status');
    Route::post('/shipment/update-status', [KelolaStatusController::class, 'konfirmasiStatus'])->name('shipment.updateStatus');
    Route::get('/history_pengiriman_kurir', [KelolaStatusController::class, 'history'])->name('kurir.history_pengiriman_kurir');
    Route::get('/resi/{shipmentID}/download', [KelolaStatusController::class, 'downloadResi'])->name('kurir.downloadResi');
    Route::get('/print-resi/{shipmentID}', [KelolaStatusController::class, 'printResi'])->name('kurir.printResi');
    
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('courier.profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('courier.profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('courier.profile.destroy');
}); 

require __DIR__.'/auth.php';

// Utility routes
Route::get('/force-logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
});

Route::post('/tarif/hitung', [TarifController::class, 'hitungTarif'])->name('tarif.hitung');
Route::prefix('admin/pengiriman')->name('admin.')->group(function () {
    Route::get('download/{shipmentID}', [AdminShipmentController::class, 'downloadResi'])->name('downloadResi');
    Route::get('print/{shipmentID}', [AdminShipmentController::class, 'printResi'])->name('printResi');
});

  Route::get('/print-resi/{shipmentID}', [ShipmentController::class, 'printResi'])->name('User.printResi');