<?php

use Illuminate\Support\Facades\Route;

Route::put('/change-password', 'App\Http\Controllers\UserController@changePassword');
Route::get('/users', 'App\Http\Controllers\UserController@getAll')
    ->middleware('admin');
