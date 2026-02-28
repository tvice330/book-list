<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/swagger', 'swagger.index');

Route::get('/swagger/openapi.json', function () {
    return response()->file(
        resource_path('swagger/openapi.json'),
        ['Content-Type' => 'application/json']
    );
});
