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
            if (empty($model->group_id)) {
                $model->group_id = Auth::user()->current_group_id;
            }
        });
    }
}
