<?php 

namespace App\Models;

use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CurrentStateCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'str_id',
        'group_id',
        'currency_id',
        'title',
        'desc'
    ];

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

    public function group()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function currentStates()
    {
        return $this->hasMany(CurrentState::class, 'category_id');
    }
}

