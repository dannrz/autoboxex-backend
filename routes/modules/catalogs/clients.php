<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Catalogs\ClientController::class, 'index']);
Route::get('/{client}/vehicles', [\App\Http\Controllers\Catalogs\ClientController::class, 'vehicles']);
