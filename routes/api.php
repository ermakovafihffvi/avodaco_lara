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
            Route::post('/update', 'IncomesController@update');
            Route::delete('/{income}/delete', 'IncomesController@delete');
        });

        Route::prefix('expense')->group(function () {
            Route::get('/total', 'ExpensesController@getTotalExpenses');
            Route::get('/user/{user}', 'ExpensesController@getUserExpenses');
            Route::get('/categories', 'ExpensesController@getAllCategories');
            Route::post('/update', 'ExpensesController@update');
            Route::delete('/{expense}/delete', 'ExpensesController@delete');
        });

        Route::prefix('saving')->group(function () {
            Route::get('/categories', 'SavingsController@getCategories');
        });

        Route::prefix('expenses-category')->group(function () {
            Route::delete('/{category}/delete', 'ExpensesController@deleteExpCategory');
            Route::put('/{category}/update', 'ExpensesController@updateExpCategory');
            Route::post('/add', 'ExpensesController@addExpCategory');
        });

        Route::prefix('savings-category')->group(function () {
            Route::delete('/{category}/delete', 'SavingsController@deleteSavingsCategory');
            Route::put('/{category}/update', 'SavingsController@updateSavingsCategory');
            Route::post('/add', 'SavingsController@addSavingsCategory');
        });

        Route::prefix('dashboard')->group(function () {
            Route::post('/last_incomes', 'IncomesController@getLastIncomes');
            Route::post('/last_expenses', 'ExpensesController@getLastExpenses');
            Route::post('/last_states', 'CurrentStateController@getLastStates');
        });

        Route::prefix('state')->group(function () {
            Route::get('/categories', 'CurrentStateController@getCategories');
            Route::delete('/{category}/delete', 'CurrentStateController@deleteCategory');
            Route::put('/{category}/update', 'CurrentStateController@updateCategory');
            Route::post('/add', 'CurrentStateController@addCategory');
        });

        Route::get('/currencies', 'CurrencyController@getAllCurrencies');
        Route::post('/{currency}/set-rate', 'CurrencyController@setRate');
        Route::post('/add-currency', 'CurrencyController@addRate');
        
        Route::get('/users-list', 'UserController@getUsersList');

        Route::get('/current-user', function () {
            return Auth::user();
        });
    });
