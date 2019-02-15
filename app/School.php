<?php

namespace App;


class School extends Entity
{
    protected $table = 'school';

    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function type()
    {
        return $this->belongsTo('App\TypeSchool');
    }
}
