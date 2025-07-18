<?php

namespace App\Models;

use App\Models\Scopes\BasePeriodScope;
use App\Models\Scopes\GroupScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Expense extends Model
{
    use HasFactory;
    public $table = "expenses";
    public $timestamps = false;
    protected $fillable = array('category_id', 'user_id', 'desc', 'sum', 'created_at');
    protected $appends = ['is_repeated_monthly', 'repeats_times'];

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

    public function category()
    {
        return $this->belongsTo(CategoryExp::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repeatable()
    {
        return $this->hasOne(RepeatableExpense::class);
    }

    public function isRepeatedMonthly(): Attribute
    {
        return Attribute::get(fn () => optional($this->repeatable)->is_every_month ?? false);
    }

    public function repeatsTimes(): Attribute
    {
        return Attribute::get(fn () => optional($this->repeatable)->times ?? 0);
    }
}
