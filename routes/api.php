<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(fn() => require __DIR__ . '/../routes/modules/auth.php');
    Route::prefix('services')->group(fn() => require __DIR__ . '/../routes/modules/services.php')->middleware('auth:sanctum');

    Route::get('/', function () {
        return response()->json(
            [
                'message'    => 'Welcome to the API!',
                'path' => __DIR__ . '/../routes/api.php',
            ]
        );
    })->middleware('auth:sanctum');
});