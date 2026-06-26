<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(fn() => require __DIR__ . '/../routes/modules/auth.php');
    Route::prefix('services')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/services.php');
    Route::prefix('menu')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/menu.php');
    Route::prefix('user')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/user.php');
    Route::prefix('catalogs')->middleware('auth:sanctum')->group(fn() => require __DIR__ . '/../routes/modules/catalogs.php');
    Route::prefix('exports')->middleware('auth:sanctum')->group(function () {
        Route::get('/excel',              'App\Http\Controllers\ExportController@excel');
        Route::get('/pdf',                'App\Http\Controllers\ExportController@pdf');
        Route::get('/excel/all',          'App\Http\Controllers\ExportController@excelAll');
        Route::get('/presupuesto/pdf',    'App\Http\Controllers\ExportController@presupuestoPdf');
        Route::get('/presupuesto/search', 'App\Http\Controllers\ExportController@searchPresupuesto');
    });
});
