<?php

namespace App\Http\Controllers;

use App\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function showAll()
    {
        return response()->json(Gender::all());
    }

}