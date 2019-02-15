<?php

namespace App;

class Evaluation extends Entity
{
    protected $table = 'evaluation';
    
    public function networkUser()
    {
        return $this->belongsTo('App\NetworkUser');
    }


}
