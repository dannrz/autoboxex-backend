<?php

use Illuminate\Support\Facades\Route;

Route::get('/brands', '\App\Http\Controllers\CatalogsController@getBrands');
Route::post('/brands', '\App\Http\Controllers\CatalogsController@saveBrand');
Route::delete('/brands/{id}', '\App\Http\Controllers\CatalogsController@deleteBrand');
Route::put('/brands/{id}', '\App\Http\Controllers\CatalogsController@updateBrand');
Route::get('/models', '\App\Http\Controllers\CatalogsController@getModelos');
