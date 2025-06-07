<?php

namespace App\Models;

use App\Models\Scopes\GroupScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryExp extends Model
{
    use HasFactory;
    use SoftDeletes;
    public $table = "category_exp";
    public $timestamps = false;
    protected $fillable = array('title', 'str_id', 'limit', 'isActive', 'currency_id', 'desc', 'special');

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new GroupScope);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expenses::class);
    }
}
