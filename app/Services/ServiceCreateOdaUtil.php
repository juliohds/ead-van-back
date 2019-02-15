<?php

namespace App\Services;

use App\MainObject;
use App\TargetObject;
use App\Oda;
use App\ClassPlan;
use App\DevelopmentResource;
use App\Course;


use App\Helpers\FacetHelper;
use Illuminate\Http\Request;

class ServiceCreateOdaUtil{

    public function createNewOda($oda_type, $oda){
        
        $odaTypeMethod = $oda_type.'Create';
        $oda_new = $this->$odaTypeMethod();
        $oda_new->fill($oda);
        return $oda_new;       
       
    }
    public function development_resourceCreate(){
        $oda = new DevelopmentResource;
        return $oda;
    }
    public function courseCreate(){
        $oda = new Course;
        return $oda;
    }
    public function odaCreate(){
        $oda = new Oda;
        return $oda;
    }
    public function class_planCreate(){
        $oda = new ClassPlan;
        return $oda;
    }

}