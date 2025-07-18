<?php

namespace App\Models;

use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SavingSource extends Model
{
    use HasFactory;
    public $table = "saving_source";

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
}
