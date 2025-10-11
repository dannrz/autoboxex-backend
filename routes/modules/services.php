<?php

use Illuminate\Support\Facades\Route;

Route::get('/tools', "\App\Http\Controllers\CarPartsController@all")
    ->name('tools.all')
    ->middleware('admin');

Route::get('/insumos', "\App\Http\Controllers\CarPartsController@getInsumos")
    ->name('insumos.all');

Route::get('/precios', "\App\Http\Controllers\CarPartsController@getPrecios")
    ->name('precios.all');

Route::get('/costos', "\App\Http\Controllers\CarPartsController@getCostos")
    ->name('costos.all');

Route::get('/', "\App\Http\Controllers\CatalogsController@getServices")
    ->name('services.all');

Route::get('/states', "\App\Http\Controllers\CatalogsController@getStates")
    ->name('states.all');

Route::get('/clients', 'App\Http\Controllers\ServicesController@getClients')
    ->name('clients.all');

Route::get('/insumos', 'App\Http\Controllers\ServicesController@getInsumos')
    ->name('insumos.all');
