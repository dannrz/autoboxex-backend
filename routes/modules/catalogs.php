<?php

use Illuminate\Support\Facades\Route;

Route::get('/brands', '\App\Http\Controllers\CatalogsController@getBrands');
Route::post('/brands', '\App\Http\Controllers\CatalogsController@saveBrand');
Route::delete('/brands/{id}', '\App\Http\Controllers\CatalogsController@deleteBrand');
Route::put('/brands/{id}', '\App\Http\Controllers\CatalogsController@updateBrand');
Route::get('/models', '\App\Http\Controllers\CatalogsController@getModelos');
Route::post('/models', '\App\Http\Controllers\CatalogsController@createModel');
Route::delete('/models', '\App\Http\Controllers\CatalogsController@deleteModel');
Route::put('/models', '\App\Http\Controllers\CatalogsController@updateModel');
