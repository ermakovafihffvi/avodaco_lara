<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $table = "currency";
    public $timestamps = false;
    protected $fillable = array('title', 'str_id', 'rate');
}