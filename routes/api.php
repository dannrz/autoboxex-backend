<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(fn() => require __DIR__ . '/../routes/modules/auth.php');
    Route::prefix('services')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/services.php');
    Route::prefix('menu')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/menu.php');
    Route::prefix('user')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/user.php');
});
