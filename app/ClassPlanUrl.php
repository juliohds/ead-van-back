<?php

namespace App;


class ClassPlanUrl extends Entity
{
    protected $table = 'class_plan_url';
    public $timestamps = false;
    protected $hidden = array('created_at', 'updated_at');
    
    public function classPlan(){
        return $this->belongsTo('App\ClassPlan');
    }

}
