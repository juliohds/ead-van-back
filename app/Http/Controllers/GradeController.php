<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function showAll()
    {
        return response()->json(Grade::all());
    }

}