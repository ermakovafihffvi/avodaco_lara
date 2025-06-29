<?php


namespace App\Http\Controllers;

use App\Models\CurrentState;
use App\Models\CurrentStateCategory;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CurrentStateController extends Controller
{
    public function getCategories() 
    {
        $categories = CurrentStateCategory::get();
        return Response::json($categories);
    }

    public function deleteCategory(CurrentStateCategory $category)
    {
        $category->delete();
        return Response::json();
    }

    public function updateCategory(CurrentStateCategory $category, Request $request)
    {
        $category->update([
            $request->input('field') => trim($request->input('value'))
        ]);
        $category->save();
        return Response::json();
    }

    public function addCategory(Request $request) 
    {
        $category = CurrentStateCategory::create([
            'title' => trim($request->input('title')), 
            'str_id' => trim($request->input('str_id')), 
            'currency_id' => $request->input('currency'), 
            'desc' => trim($request->input('desc'))
        ]);
        $category->save();
        return Response::json($category);
    }

    public function getLastStates(Request $request)
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

        $result = CurrentState::query()
            ->whereBetween('pseudo_month', [$start->format('Y-m'), $end->format('Y-m')])
            ->select([
                'user_id',
                'category_id',
                'pseudo_month',
                DB::raw('SUM(sum) as sum'),
            ])
            ->groupBy(
                'user_id',
                'category_id',
                'pseudo_month'
            )
            ->orderBy('pseudo_month')
            ->get();

        return Response::json($result);
    }

    public function updateState(Request $request)
    {
        $state = CurrentState::firstOrNew([
            'user_id' => $request->input('user_id'),
            'category_id' => $request->input('category_id'),
            'pseudo_month' => $request->input('pseudo_month')
        ]);
        $state->sum = $request->input('sum');
        $state->save();

        return Response::json($state);
    }
}