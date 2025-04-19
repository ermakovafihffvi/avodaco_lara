<?php

namespace App\Http\Controllers;

use App\Models\CategoryExp;
use App\Models\Expenses;
use App\Models\User;

class ExpensesController extends Controller
{
    public function getTotalExpenses()
    {
        $totalExpenses = Expenses::selectRaw('category_id, SUM(sum) as total')
            ->groupBy('category_id')
            ->get();
        return response()->json($totalExpenses);
    }

    public function getUserExpenses(User $user)
    {
        $expenses = Expenses::where('user_id', $user->id)->get();
        return response()->json($expenses);
    }

    public function addUserExpense()
    {

    }

    public function updateUserExpense()
    {

    }

    public function getAllCategories()
    {
        return response()->json(CategoryExp::where('isActive', true)->get());
    }
}