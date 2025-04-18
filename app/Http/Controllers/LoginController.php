<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            //$request->session()->regenerate();

            return response()->json([], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 422);
    }

    public function logout()
    {
        Auth::logout(Auth::user());
        return response()->json([], 200);
    }
}