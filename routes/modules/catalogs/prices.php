<?php

use App\Http\Controllers\Catalogs\PricesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PricesController::class, 'index']);
