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
        $currency->rate = trim($request->rate);
        $currency->save();
        return response()->json($currency);
    }

    public function addRate(Request $request)
    {
        $currency = new Currency();
        $currency->title = trim($request->title);
        $currency->str_id = trim($request->str_id);
        $currency->rate = trim($request->rate);
        $currency->save();
        return response()->json($currency);
    }
}