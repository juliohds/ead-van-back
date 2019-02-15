<?php

namespace App;


class Interest extends Entity
{
    protected $table = 'interest';
    protected $primaryKey = 'id';
    protected $hidden = array('created_at', 'updated_at');

    public function academics() {
        return $this->belongsToMany('App\Academic','academic_interest','interest_id', 'academic_id');
    }
}
