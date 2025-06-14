<?php

namespace App\Models;

use App\Models\Scopes\BasePeriodScope;
use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Income extends Model
{
    use HasFactory;
    public $table = "income";
    public $timestamps = false;
    protected $fillable = array('user_id', 'desc', 'sum', 'created_at', 'currency_id');

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new GroupScope);
        //static::addGlobalScope(new BasePeriodScope);

        static::creating(function ($model) {
            if (empty($model->group_id)) {
                $model->group_id = Auth::user()->current_group_id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
