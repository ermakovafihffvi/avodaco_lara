<?php

namespace App\Http\Controllers;

use App\Models\Income;
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
        $months = [];
        $from = Carbon::createFromFormat('Y-m-d', $request->input('from') . '-' . UserGroup::XDATE)->format('Y-m-d');

        $start = Carbon::createFromFormat('Y-m', $request->input('from'));
        $end = Carbon::createFromFormat('Y-m-d', $request->input('to') . '-' . UserGroup::XDATE);

        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start->addMonth();
        }

        $end = $end->addDay()->format('Y-m-d');

        //total
        //DB::enableQueryLog();
        $monthlyIncomes = DB::table('income')
            ->join('currency', 'income.currency_id', '=', 'currency.id')
            ->selectRaw('
                DATE_FORMAT(income.created_at, "%Y-%m") as month,
                ROUND(SUM(income.sum / currency.rate), 2) as total
            ')
            ->whereBetween('income.created_at', [$from . ' 00:00:00', $end . ' 00:00:00'])
            ->where('income.group_id', Auth::user()->current_group_id)
            ->groupBy('month')
            ->pluck('total', 'month');

        //dump(DB::getQueryLog());
        $complete = collect($months)->mapWithKeys(fn($month) => [
            $month => $monthlyIncomes[$month] ?? 0
        ]);


        //for users
        //DB::enableQueryLog();
        $results = DB::table('income')
            ->join('currency', 'income.currency_id', '=', 'currency.id')
            ->selectRaw('
                income.user_id,
                DATE_FORMAT(income.created_at, "%Y-%m") as month,
                ROUND(SUM(income.sum / currency.rate), 2) as total
            ')
            ->whereBetween('income.created_at', [$from . ' 00:00:00', $end . ' 00:00:00'])
            ->where('income.group_id', Auth::user()->current_group_id)
            ->groupBy('income.user_id', 'month')
            ->orderBy('income.user_id')
            ->orderBy('month')
            ->get();
        //dump(DB::getQueryLog());
        //dump($results);

        $users = $results->groupBy('user_id')->map(function ($results) use ($months) {
        
            $recordsByMonth = $results->pluck('total', 'month');
        
            return collect($months)->mapWithKeys(fn($month) => [
                $month => $recordsByMonth[$month] ?? 0,
            ]);
        });

        return Response::json([
            'total' => $complete,
            'users' => $users
        ]);
    }
}