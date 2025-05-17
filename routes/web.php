<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;

Route::middleware('guest')->group(function(){
    Route::get('/login', function () {
        return view('pages.guest.login');
    });
    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'store'])->name('store');
});

Route::middleware('auth')->group(function(){
    Route::get('/', function(){
        return view('pages.auth.home');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post('/deposit', [AccountController::class, 'makeDeposit'])->name('make.deposit');

    Route::get('/load-historic', [AccountController::class, 'loadHistoricTransactions'])->name('load.historic.transactions');

    Route::post('/revert-transaction', [AccountController::class, 'revertTransaction'])->name('revert.transaction');
});