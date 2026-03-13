<?php

use App\Http\Controllers\Catalogs\ModelsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ModelsController::class, 'index']);
Route::post('/', [ModelsController::class, 'store']);
Route::delete('/', [ModelsController::class, 'destroy']);
Route::put('/', [ModelsController::class, 'update']);
