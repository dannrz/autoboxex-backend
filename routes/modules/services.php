<?php

use Illuminate\Support\Facades\Route;

Route::get('/tools', "\App\Http\Controllers\CarPartsController@all")
    ->name('tools.all')
    ->middleware('admin');

Route::get('/', "\App\Http\Controllers\CatalogsController@getServices")
    ->name('services.all');

Route::get('/states', "\App\Http\Controllers\CatalogsController@getStates")
    ->name('states.all');
