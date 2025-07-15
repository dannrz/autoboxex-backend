<?php

use Illuminate\Support\Facades\Route;

Route::get('/services', function () {
    return response()->json([
        'message' => 'List of services',
        'services' => [
            'service1' => 'Description of service 1',
            'service2' => 'Description of service 2',
        ],
    ]);
})->name('services.list');