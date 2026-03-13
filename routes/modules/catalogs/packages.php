<?php

use App\Http\Controllers\Catalogs\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PackageController::class, 'index']);
