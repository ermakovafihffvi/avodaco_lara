<?php

namespace App\Models;

use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Savings extends Model
{
    use HasFactory;
    public $table = "savings";
    public $timestamps = false;
    protected $fillable = array('source_id', 'category_id', 'sum', 'created_at');

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

    public function category()
    {
        return $this->belongsTo(CategorySavings::class, 'category_id');
    }

    public function source()
    {
        return $this->belongsTo(SavingSource::class, 'category_id');
    }
}
