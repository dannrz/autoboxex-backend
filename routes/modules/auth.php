<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
Route::post('request-password-change', [UserController::class, 'requestPasswordRestore'])->name('auth.requestPasswordChange');
