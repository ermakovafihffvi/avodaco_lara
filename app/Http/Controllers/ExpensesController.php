<?php

namespace App\Http\Controllers;

use App\Models\CategoryExp;
use App\Models\Expenses;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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

    public function updateExpense(Request $expenseData)
    {
        if ($expenseData->id) {
            $expense = Expenses::find($expenseData->id);
        } else {
            $expense = new Expenses();
        }
        $expense->created_at = (new Carbon($expenseData->date))->toDateTimeString();
        $expense->desc = $expenseData->description;
        $expense->sum = $expenseData->sum;
        $expense->user_id = $expenseData->user_id;
        $expense->category_id = CategoryExp::select('id')->where('str_id', $expenseData->category_str)->first()->id;
        $expense->save();
        return Response::json($expense);
    }

    public function getAllCategories(Request $request)
    {
        $isSpecial = $request->query('special') ?? 0;
        return response()->json(CategoryExp::where('isActive', true)->where('special', $isSpecial)->get());
    }
}