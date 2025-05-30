<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        Route::get('/{user}/incomes', 'IncomesController@getUserIncomes');
        Route::get('/users-list', 'UserController@getUsersList');
        Route::get('/expenses-categories', 'ExpensesController@getAllCategories');

        Route::get('/current-user', function () {
            return Auth::user();
        });

        Route::post('/update-expense', 'ExpensesController@updateExpense');
        Route::post('/update-income', 'IncomesController@updateIncome');
    });
