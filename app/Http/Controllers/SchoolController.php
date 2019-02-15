<?php

namespace App\Http\Controllers;

use App\School;

class SchoolController extends Controller
{
    public function showByTypeAndCity()
    {
        $this->validate($this->request, [
            'type_id' => 'required',
            'city_id' => 'required',
        ]);

        $schools =  School::where([['type_id','=',$this->request->input('type_id')],['city_id','=',$this->request->input('city_id')]])->get();        
        return response()->json($schools);
    }

}