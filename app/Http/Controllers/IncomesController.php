<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Scopes\BasePeriodScope;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function update(Request $incomeData)
    {
        if ($incomeData->id) {
            $income = Income::find($incomeData->id);
        } else {
            $income = new Income();
        }
        $income->created_at = (new Carbon($incomeData->date))->toDateTimeString();
        $income->desc = trim($incomeData->description);
        $income->sum = trim($incomeData->sum);
        $income->user_id = $incomeData->user_id ?? Auth::user()->id;
        $income->currency_id = $incomeData->currency_id;
        $income->save();
        return Response::json($income);
    }

    public function delete(Income $income)
    {
        $income->delete();
        return Response::json();
    }

    public function getLastIncomes(Request $request)
    {
        $dateRange = $request->input('date');
        $xDate = UserGroup::XDATE;

        $today = Carbon::today()->day;
        if ($today > 22) {
            $start = Carbon::create($dateRange[0]['year'], $dateRange[0]['month'] + 1, $xDate + 1)->startOfDay();
            $end = Carbon::create($dateRange[1]['year'], $dateRange[1]['month'] + 2, $xDate)->endOfDay();
        } else {
            $start = Carbon::create($dateRange[0]['year'], $dateRange[0]['month'], $xDate + 1)->startOfDay();
            $end = Carbon::create($dateRange[1]['year'], $dateRange[1]['month'] + 1, $xDate)->endOfDay();
        }

        $totals = Income::withoutGlobalScope(BasePeriodScope::class)
            ->whereBetween('created_at', [$start, $end])
            ->select([
                'user_id',
                'currency_id',
                DB::raw("DATE_FORMAT(DATE_SUB(created_at, INTERVAL {$xDate} DAY), '%Y-%m') as pseudo_month"),
                DB::raw('SUM(sum) as total_income'),
            ])
            ->groupBy(
                'user_id',
                'currency_id',
                DB::raw("DATE_FORMAT(DATE_SUB(created_at, INTERVAL {$xDate} DAY), '%Y-%m')")
            )
            ->orderBy('pseudo_month')
            ->get();

        return Response::json($totals);
    }
}