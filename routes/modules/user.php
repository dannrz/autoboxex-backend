<?php

use Illuminate\Support\Facades\Route;

Route::put('/change-password', 'App\Http\Controllers\UserController@changePassword');
