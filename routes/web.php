<?php

use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return  redirect('/login');
});

Route::get('/dashboard', function () {
    return  redirect('/');
});

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/my-car', [CarController::class,'index'])->name('car.index');
    Route::post('/my-car', [CarController::class,'store'])->name('car.store');
    Route::post('/my-car/{id}', [CarController::class,'update'])->name('car.update');
    Route::delete('/my-car/{id}', [CarController::class,'destroy'])->name('car.destroy');

    Route::get('/rent-car', [RentController::class,'index'])->name('rent.index');
    Route::post('/rent-car', [RentController::class,'store'])->name('rent.store');

    Route::get('/borrow-car', [BorrowController::class,'index'])->name('borrow.index');
    Route::post('/borrow-car', [BorrowController::class,'store'])->name('borrow.store');

    Route::get('/history', [HistoryController::class,'index'])->name('history.index');
});

require __DIR__.'/auth.php';
