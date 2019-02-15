<?php

namespace App\Http\Controllers;

use App\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function showAll()
    {
        return response()->json(Interest::all());
    }

}