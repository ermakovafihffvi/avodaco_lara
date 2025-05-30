<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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

    public function updateIncome(Request $incomeData)
    {
        if ($incomeData->id) {
            $income = Income::find($incomeData->id);
        } else {
            $income = new Income();
        }
        $income->created_at = (new Carbon($incomeData->date))->toDateTimeString();
        $income->desc = $incomeData->description;
        $income->sum = $incomeData->sum;
        $income->user_id = $incomeData->user_id;
        $income->currency_id = $incomeData->currency_id;
        $income->save();
        return Response::json($income);
    }
}