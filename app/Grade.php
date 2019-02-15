<?php

namespace App;

class Grade extends Entity
{
    protected $table = 'grade';
    protected $hidden = array('created_at', 'updated_at');

    public function academics()
    {
        return $this->belongsToMany('App\Academic', 'academic_grade', 'grade_id', 'academic_id');
    }
}
