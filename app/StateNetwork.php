<?php

namespace App;

class StateNetwork extends Entity
{
    protected $table = 'state_network';

    public function network(){
        return $this->belongsTo('App\Network');
    }
    public function state(){
        return $this->belongsTo('App\State');
    }
}
