<?php

namespace App\Http\Controllers;

use App\Models\CategoryExp;
use App\Models\Expenses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    public function getTotalExpenses()
    {
        $totalExpenses = Expenses::selectRaw('category_id, SUM(sum) as total')
            ->groupBy('category_id')
            ->get();
        return response()->json($totalExpenses);
    }

    public function getUserExpenses(Request $request, User $user)
    {
        $isSpecial = $request->query('special') ?? 0;
        //DB::enableQueryLog();
        $expenses = Expenses::whereHas('category', function ($q) use ($isSpecial) {
                $q->where('special', $isSpecial)->where('isActive', true);
            })
            ->where('user_id', $user->id)
            ->get();
        //dump(DB::getQueryLog());
        return response()->json($expenses);
    }

    public function addUserExpense()
    {

    }

    public function updateUserExpense()
    {

    }

    public function getAllCategories(Request $request)
    {
        $isSpecial = $request->query('special') ?? 0;
        return response()->json(CategoryExp::where('isActive', true)->where('special', $isSpecial)->get());
    }
}