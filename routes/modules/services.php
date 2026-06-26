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

Route::get('/clients', 'App\Http\Controllers\ServicesController@getClients')
    ->name('clients.all');

Route::get('/insumos', 'App\Http\Controllers\ServicesController@getInsumos')
    ->name('insumos.all');

Route::get('/oe', 'App\Http\Controllers\ServicesController@getInOrders')
    ->name('orders.all')
    ->middleware('multiadmin');

Route::get('/plates', "App\Http\Controllers\ServicesController@getPlates")
    ->name('plates.all')
    ->middleware('multiadmin');

Route::get('/search', 'App\Http\Controllers\ServicesController@search')
    ->name('services.search');

Route::get('/marcas', 'App\Http\Controllers\ServicesController@getMarcas')
    ->name('marcas.all');

Route::get('/modelos', 'App\Http\Controllers\ServicesController@getModelos')
    ->name('modelos.all');

Route::post('/', "App\Http\Controllers\ServicesController@store")
    ->name('services.store');
