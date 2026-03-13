<?php

use App\Http\Controllers\Catalogs\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BrandController::class, 'index']);
Route::post('/', [BrandController::class, 'store']);
Route::delete('/{id}', [BrandController::class, 'destroy']);
Route::put('/{id}', [BrandController::class, 'update']);
