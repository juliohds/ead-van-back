<?php

namespace App;


class ClassPlanOda extends Entity
{
    protected $table = 'class_plan_oda';
    protected $hidden = array('created_at', 'updated_at');
    
    public function oda(){
        return $this->belongsTo('App\Oda');
    }
    public function classPlan(){
        return $this->belongsTo('App\ClassPlan');
    }

}
