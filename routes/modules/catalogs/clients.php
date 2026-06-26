<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Catalogs\ClientController::class, 'index']);
Route::post('/', [\App\Http\Controllers\Catalogs\ClientController::class, 'store']);
Route::put('/{id}', [\App\Http\Controllers\Catalogs\ClientController::class, 'update']);
