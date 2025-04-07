<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::namespace("App\\Http\\Controllers")
    ->group(function () {
        Route::get('/first', 'FirstController@index');
    });
