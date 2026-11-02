<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/ringkasan', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/input-suara', [TpsController::class, 'create'])->name('tps.create');
    Route::post('/input-suara', [TpsController::class, 'store'])->name('tps.store');
    Route::get('/input-suara/{tps}/edit', [TpsController::class, 'edit'])->name('tps.edit');
    Route::put('/input-suara/{tps}', [TpsController::class, 'update'])->name('tps.update');

    Route::get('/data-tps', [TpsController::class, 'index'])->name('tps.index');
    Route::get('/data-tps/export', [TpsController::class, 'export'])->name('tps.export');
    Route::get('/data-tps/pdf', [TpsController::class, 'pdf'])->name('tps.pdf');
    Route::delete('/data-tps/{tps}', [TpsController::class, 'destroy'])->name('tps.destroy');

    Route::get('/kandidat', [CandidateController::class, 'index'])->name('candidates.index');
    Route::post('/kandidat', [CandidateController::class, 'store'])->name('candidates.store');
    Route::put('/kandidat/{candidate}', [CandidateController::class, 'update'])->name('candidates.update');
    Route::delete('/kandidat/{candidate}', [CandidateController::class, 'destroy'])->name('candidates.destroy');

    Route::get('/petugas', [UserController::class, 'index'])->name('users.index');
    Route::post('/petugas', [UserController::class, 'store'])->name('users.store');
    Route::delete('/petugas/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/akun', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/akun', [AccountController::class, 'update'])->name('account.update');
});