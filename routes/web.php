<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SerialCodeController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/serial-codes', [SerialCodeController::class, 'index'])->name('serial-codes.index');
    Route::post('/serial-codes', [SerialCodeController::class, 'store'])->name('serial-codes.store');
    Route::get('/serial-codes/export', [SerialCodeController::class, 'export'])->name('serial-codes.export');
    Route::get('/serial-codes/{serialCode}', [SerialCodeController::class, 'show'])->name('serial-codes.show');
});
