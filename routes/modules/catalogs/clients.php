<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\Catalogs\ClientController::class, 'index']);
