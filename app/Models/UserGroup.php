<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;
    public $table = "user_group";

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_to_group', 'group_id', 'user_id');
    }
}