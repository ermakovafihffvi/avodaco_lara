<?php

namespace App\Models\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BasePeriodScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $now = Carbon::now();
        $arr = [];
        if($now->day <= 22){
            $arr = [
                "start_date" => Carbon::create($now->year, $now->month - 1, "23", 0, 0, 0, $now->timezone),
                "end_date" => Carbon::create($now->year, $now->month, "23", 0, 0, 0, $now->timezone),
            ];
        } else {
            $arr = [
                "start_date" => Carbon::create($now->year, $now->month, "23", 0, 0, 0, $now->timezone),
                "end_date" => Carbon::create($now->year, $now->month + 1, "23", 0, 0, 0, $now->timezone),
            ];
        }
        $builder->where('income.created_at', ">", $arr['start_date'])->where('income.created_at', "<", $arr['end_date']);
    }
}
