<?php

use App\Http\Controllers\Catalogs\RefaccionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RefaccionController::class, 'index']);
Route::get('/last-id', [RefaccionController::class, 'getLastId']);
Route::post('/', [RefaccionController::class, 'store']);
Route::delete('/{id}', [RefaccionController::class, 'destroy']);
Route::put('/{id}', [RefaccionController::class, 'update']);
