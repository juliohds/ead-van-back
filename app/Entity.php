<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model 
{
    public function __construct($options = []){
        foreach($options as $k => $v){
            $this->$k = $v;
        }
    }
 
}    