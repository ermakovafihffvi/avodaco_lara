<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', 'App\\Http\\Controllers\\LoginController@login');
Route::post('/logout', 'App\\\Http\\Controllers\\LoginController@logout');

Route::namespace("App\\Http\\Controllers")
    ->middleware('auth:sanctum', 'api')
    ->group(function () {
        Route::get('/first', 'FirstController@index');
        Route::get('/total-incomes', 'IncomesController@getTotalIncomes');
        Route::get('/total-expenses', 'ExpensesController@getTotalExpenses');
        Route::get('/currencies', 'CurrencyController@getAllCurrencies');
        Route::post('/{currency}/set-rate', 'CurrencyController@setRate');

        Route::get('/{user}/expenses', 'ExpensesController@getUserExpenses');
        Route::get('/{user}/incomes', 'IncomeController@getUserIncomes');

    });
