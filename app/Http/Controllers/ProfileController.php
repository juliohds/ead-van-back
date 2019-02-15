<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showAll()
    {
        return response()->json(Profile::all());
    }
    
}