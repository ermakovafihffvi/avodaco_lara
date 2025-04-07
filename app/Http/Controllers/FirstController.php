<?php

namespace App\Http\Controllers;
 
 use App\Models\User;
 use Illuminate\View\View;
  
 class FirstController extends Controller
 {
     /**
      * Show the profile for a given user.
      */
     public function index()
     {
         return response()->json([ 'data' => 'Hello']);
     }
 }