<?php

namespace App;

class FavorityTargetObject extends Entity
{
    protected $table = 'favority_target_object';
    
    protected $hidden = array('created_at', 'updated_at');

    public function target_object(){
        return $this->belongsTo('App\TargetObject');
    }

}


