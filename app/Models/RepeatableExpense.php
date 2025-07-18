<?php

namespace App\Models;

use App\Models\Scopes\BasePeriodScope;
use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RepeatableExpense extends Model
{
    use SoftDeletes;

    protected $table = 'repeatable_expenses';

    protected $fillable = [
        'expense_id',
        'is_every_month',
        'times',
    ];

    protected $casts = [
        'is_every_month' => 'boolean',
        'times' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new GroupScope);
        static::addGlobalScope(new BasePeriodScope);

        static::creating(function ($model) {
            $user = Auth::user();
            if (empty($model->group_id) && $user) {
                $model->group_id = $user->current_group_id;
            }
        });
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'expense_id');
    }
}
