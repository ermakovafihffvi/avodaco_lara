<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function getAllCurrencies()
    {
        $currencies = Currency::all();
        return response()->json($currencies);
    }

    public function setRate(Currency $currency, Request $request)
    {
        $currency->rate = $request->rate;
        $currency->save();
        return response();
    }
}