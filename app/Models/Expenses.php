<?php

namespace App\Models;

use App\Models\Scopes\BasePeriodScope;
use App\Models\Scopes\GroupScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    public $table = "expenses";
    public $timestamps = false;
    protected $fillable = array('category_id', 'user_id', 'desc', 'sum', 'created_at');

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

    public function category()
    {
        return $this->belongsTo(CategoryExp::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
