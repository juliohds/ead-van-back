<?php

namespace App\Http\Controllers;

use App\TypeSchool;
use Illuminate\Http\Request;

class TypeSchoolController extends Controller
{
    public function showAll()
    {
        return response()->json(TypeSchool::all());
    }

}