<?php

use App\Models\Expense;
use App\Models\RepeatableExpense;
use App\Models\Scopes\GroupScope;
use App\Models\UserGroup;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Psy\Readline\Userland;

//Artisan::command('inspire', function () {
//    $this->comment(Inspiring::quote());
//})->purpose('Display an inspiring quote');

Schedule::call(function () {
    RepeatableExpense::withoutGlobalScope(GroupScope::class)->where('is_every_month', true)->with(['expense' => function ($query) {
            $query->withoutGlobalScope(GroupScope::class);
        }])->chunk(100, function ($repeatables) {
        $insertData = [];

        foreach ($repeatables as $repeatable) {
            $expense = $repeatable->expense;
            if (!$expense) continue;

            $insertData[] = [
                'group_id'    => $expense->group_id,
                'desc'        => $expense->desc,
                'category_id' => $expense->category_id,
                'user_id'     => $expense->user_id,
                'sum'         => $expense->sum,
                'created_at'  => now()
            ];
        }

        if (!empty($insertData)) {
            Expense::withoutGlobalScope(GroupScope::class)->insert($insertData);
            \Log::info('GROUP_ID: ' . $expense->group_id . ', added ' . count($insertData) . ' expenses rows');
        }
    });

    //delete completed payments
    $now = now();
    $xDay = UserGroup::XDATE;
    $start = $now->day >= $xDay
        ? $now->copy()->day($xDay)
        : $now->copy()->subMonth()->day($xDay);

    RepeatableExpense::withoutGlobalScope(GroupScope::class)->where(function ($query) {
            $query->where('is_every_month', 0)
                ->orWhereNull('is_every_month');
        })
        ->whereNotNull('times')
        ->whereRaw("created_at < DATE_SUB(?, INTERVAL times MONTH)", [$start])
        ->delete();
    
    //after all completed deleted - add rows for the rest
    RepeatableExpense::withoutGlobalScope(GroupScope::class)->where(function ($query) {
            $query->where('is_every_month', 0)
                ->orWhereNull('is_every_month');
        })
        ->whereNotNull('times')
        ->with(['expense' => function ($query) {
            $query->withoutGlobalScope(GroupScope::class);
        }])
        ->chunk(100, function ($repeatables) {
            foreach ($repeatables as $repeatable) {
                $expense = $repeatable->expense;
                if (!$expense) continue;

                $insertData[] = [
                    'group_id'    => $expense->group_id,
                    'desc'        => $expense->desc,
                    'category_id' => $expense->category_id,
                    'user_id'     => $expense->user_id,
                    'sum'         => $expense->sum,
                    'created_at'  => now()
                ];
            }

            if (!empty($insertData)) {
                Expense::withoutGlobalScope(GroupScope::class)->insert($insertData);
                \Log::info('GROUP_ID: ' . $expense->group_id . ', added ' . count($insertData) . ' expenses rows');
            }
        });
})->monthlyOn(23, '00:00');
