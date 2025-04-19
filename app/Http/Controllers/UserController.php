<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUsersList()
    {
        return response()->json(User::select('name', 'id')
            ->whereRelation('groups', 'user_group.id', Auth::user()->current_group_id)
            ->get()
        );
    }
}