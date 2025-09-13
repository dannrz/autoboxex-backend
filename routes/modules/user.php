<?php

use Illuminate\Support\Facades\Route;

Route::put('/change-password', 'App\Http\Controllers\UserController@changePassword');
Route::get('/users', 'App\Http\Controllers\UserController@getAll')
    ->middleware('admin');
Route::get('/request-password-changes', 'App\Http\Controllers\UserController@getPasswordChangeRequests')
    ->middleware('admin');
Route::patch('/respond-password-request', 'App\Http\Controllers\UserController@respondPasswordRequest')
    ->middleware('admin');
Route::patch('change-status', 'App\Http\Controllers\UserController@changeStatusUser')
    ->middleware('admin');