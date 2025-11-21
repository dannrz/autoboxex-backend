<?php

use Illuminate\Support\Facades\Route;

Route::get('/brands', '\App\Http\Controllers\CatalogsController@getBrands');
Route::post('/brands', '\App\Http\Controllers\CatalogsController@saveBrand');
