<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;

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
});