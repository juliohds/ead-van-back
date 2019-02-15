<?php

namespace App\Http\Controllers;

use App\AbilityBncc;
use App\ComponentBncc;

class AbilityBnccController extends Controller

{
    public function searchByBncc(){
        $title = $this->input('bncc_titles') ? $this->input('bncc_titles') : "";

        $abilities = AbilityBncc::whereIn('bncc', $title)->get();
        return $this->responseOK($abilities);
    }

   public function show(){
        $startYear = $this->input('start_year') ? $this->input('start_year') : 0;
        $endYear = $this->input('end_year') ? $this->input('end_year')  : 9 ;
        $componentId = $this->input('component_bncc_id');

        $componentIds = $this->input('component_bncc_ids');
        
        if(!$componentIds){
            $componentIds = ComponentBncc::all()->pluck('id')->toArray();
        }
        $abilities = AbilityBncc::whereIn('component_bncc_id',$componentIds)
            ->where('start_year','>=',$startYear)
            ->where('end_year','<=',$endYear)->get();
        return $this->responseOK($abilities);

   }


}