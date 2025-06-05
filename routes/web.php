<?php

use App\Http\Controllers\ProfileController;
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
