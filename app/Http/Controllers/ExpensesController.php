<?php

namespace App\Http\Controllers;

use App\Models\CategoryExp;
use App\Models\Expenses;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function update(Request $expenseData)
    {
        if ($expenseData->id) {
            $expense = Expenses::find($expenseData->id);
        } else {
            $expense = new Expenses();
        }
        $expense->created_at = (new Carbon($expenseData->date))->toDateTimeString();
        $expense->desc = trim($expenseData->description);
        $expense->sum = trim($expenseData->sum);
        $expense->user_id = $expenseData->user_id ?? Auth::user()->id;
        $expense->category_id = $expenseData->category_id;
        $expense->save();
        return Response::json($expense);
    }

    public function delete(Expenses $expense)
    {
        $expense->delete();
        return Response::json();
    }

    public function getAllCategories(Request $request)
    {
        $notAll = $request->query('all') ? !$request->query('all') : true;
        $isSpecial = $request->query('special') ?? 0;
        return response()->json(CategoryExp::
            when($notAll, function($query) {
                $query->where('isActive', true);
            })
            ->when($notAll, function($query) use ($isSpecial) {
                $query->where('special', $isSpecial);
            })
            ->get());
    }

    public function deleteExpCategory(CategoryExp $category)
    {
        $category->delete();
        return Response::json();
    }

    public function updateExpCategory(CategoryExp $category, Request $request)
    {
        $category->update([
            $request->input('field') => trim($request->input('value'))
        ]);
        $category->save();
        return Response::json();
    }

    public function addExpCategory(Request $request) {
        $category = CategoryExp::create([
            'title' => trim($request->input('title')), 
            'str_id' => trim($request->input('str_id')), 
            'limit' => trim($request->input('limit')), 
            'isActive' => $request->input('isActive'), 
            'currency_id' => $request->input('currency'), 
            'desc' => trim($request->input('desc')), 
            'special' => $request->input('special')
        ]);
        $category->save();
        return Response::json($category);
    }
}