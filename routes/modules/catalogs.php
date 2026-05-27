<?php

use Illuminate\Support\Facades\Route;

Route::prefix('brands')->group(fn() => require __DIR__ . '/catalogs/brand.php');
Route::prefix('models')->group(fn() => require __DIR__ . '/catalogs/models.php');
Route::prefix('refacciones')->group(fn() => require __DIR__ . '/catalogs/refacciones.php');
Route::prefix('packages')->group(fn() => require __DIR__ . '/catalogs/packages.php');
Route::prefix('clients')->group(fn() => require __DIR__ . '/catalogs/clients.php');
