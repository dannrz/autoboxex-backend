<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'App\Http\Controllers\AuthController@login')->name('auth.login');
Route::get('logout', 'App\Http\Controllers\AuthController@logout')->name('auth.logout')->middleware('auth:sanctum');