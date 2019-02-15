<?php

namespace App\Http\Controllers;

use App\ComponentBncc;

class ComponentBnccController extends Controller

{

   public function showAll(){
        return $this->responseOK(ComponentBncc::all());

   }


}