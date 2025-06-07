<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::namespace("App\\Http\\Controllers")->group(function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/logout', 'LoginController@logout');
});

Route::namespace("App\\Http\\Controllers")
    ->middleware('auth:sanctum', 'api')
    ->group(function () {
        Route::get('/first', 'FirstController@index');

        Route::prefix('income')->group(function () {
            Route::get('/total', 'IncomesController@getTotalIncomes');
            Route::get('/user/{user}', 'IncomesController@getUserIncomes');
            Route::post('/income/update', 'IncomesController@update');
            Route::delete('/{income}/delete', 'IncomesController@delete');
        });

        Route::prefix('expense')->group(function () {
            Route::get('/total', 'ExpensesController@getTotalExpenses');
            Route::get('/user/{user}', 'ExpensesController@getUserExpenses');
            Route::get('/categories', 'ExpensesController@getAllCategories');
            Route::post('/update', 'ExpensesController@update');
            Route::delete('/{expense}/delete', 'ExpensesController@delete');
        });

        Route::delete('/expenses-category/{category}/delete', 'ExpensesController@deleteExpCategory');
        Route::delete('/savings-category/{category}/delete', 'SavingsController@deleteSavingsCategory');

        Route::get('/currencies', 'CurrencyController@getAllCurrencies');
        Route::post('/{currency}/set-rate', 'CurrencyController@setRate');
        Route::post('/add-currency', 'CurrencyController@addRate');
        
        Route::get('/users-list', 'UserController@getUsersList');

        Route::get('/current-user', function () {
            return Auth::user();
        });
    });
