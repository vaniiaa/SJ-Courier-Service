<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourierLoginController;
use App\Http\Controllers\CreateLoginController;
use App\Http\Controllers\DeleteCourierController;
use App\Http\Controllers\EditCourierController;
use App\Http\Controllers\CreateCourierController;



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
//Guest routes
Route::get('/', function () {
    return view('PublicUser.home');
});


// Authenticated user routes
Route::get('/dashboard', function () {
    return view('User.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//User routes
Route::get('/pengiriman', function () {
    return view('User.daftar_pengiriman');
})->middleware(['auth', 'verified'])->name('pengiriman');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/auth/kurir/masuk', [CourierLoginController::class, 'showLoginForm'])->name('kurir.login');
Route::post('/auth/kurir/masuk', [CourierLoginController::class, 'login']);
Route::post('/kurir/logout', [CourierLoginController::class, 'logout'])->name('kurir.logout');

Route::get('/admin/kelola_kurir', [CreateCourierController::class, 'index'])->name('admin.kelola_kurir');
Route::get('/admin/tambah_kurir', [CreateCourierController::class, 'create'])->name('admin.tambah_kurir');
Route::post('/admin/simpan_kurir', [CreateCourierController::class, 'store'])->name('admin.simpan_kurir');

Route::delete('/admin/kurir/{id}', DeleteCourierController::class)->name('admin.hapus_kurir');;

Route::get('/admin/edit_kurir/{id}', [EditCourierController::class, 'editKurir'])->name('admin.edit_kurir');
Route::put('/admin/update_kurir/{id}', [EditCourierController::class, 'updateKurir'])->name('admin.update_kurir');

Route::get('/admin/status_pengiriman', function () {
    return view('admin.status_pengiriman');
})->name('admin.status_pengiriman');


Route::get('/admin/kelola_pengiriman', function () {
    return view('admin.kelola_pengiriman');
})->name('admin.kelola_pengiriman');


Route::get('/admin/history_pengiriman', function () {
    return view('admin.history_pengiriman');
})->name('admin.history_pengiriman');

    Route::get('/admin/dashboard_admin', function () {
        return view('admin.dashboard_admin');
    })->name('admin.dashboard_admin');
