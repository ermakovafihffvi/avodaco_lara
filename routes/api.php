<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', 'App\\Http\\Controllers\\LoginController@login');

Route::namespace("App\\Http\\Controllers")
    ->middleware('auth:sanctum', 'api')
    ->group(function () {
        Route::get('/first', 'FirstController@index');
        //Route::post('/login', 'LoginController@login');
    });
