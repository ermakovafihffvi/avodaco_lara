<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;

class IncomesController extends Controller
{
    public function getTotalIncomes()
    {
        $totalIncomes = Income::selectRaw('user_id, SUM(sum) as total, currency_id')
            ->groupBy('user_id', 'currency_id')
            ->get();
        return response()->json($totalIncomes);
    }

    public function getUserIncomes(User $user)
    {
        $expenses = Income::where('user_id', $user->id)->get();
        return response()->json($expenses);
    }

    public function addUserIncome()
    {

    }

    public function updateUserIncome()
    {

    }
}