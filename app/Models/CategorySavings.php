<?php

namespace App\Models;

use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CategorySavings extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = "category_savings";
    public $timestamps = false;
    protected $fillable = array('title', 'str_id', 'limit', 'currency_id', 'desc');

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new GroupScope);

        static::creating(function ($model) {
            $user = Auth::user();
            if (empty($model->group_id) && $user) {
                $model->group_id = $user->current_group_id;
            }
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
