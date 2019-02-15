<?php

namespace App;


class Academic extends Entity
{
    protected $table = 'academic';
    protected $primaryKey = 'id';
    protected $fillable = [
        'city_id'
    ];

    public function interests() {
        return $this->belongsToMany('App\Interest','academic_interest', 'academic_id', 'interest_id');
    }
    public function grades() {
        return $this->belongsToMany('App\Grade','academic_grade', 'academic_id', 'grade_id');
    }
    public function city()
    {
        return $this->belongsTo('App\City');
    }
    public function school()
    {
        return $this->belongsTo('App\School');
    }

    public function setSchool($id){
        if(is_numeric($id)){
            $this->school_id = $id;
            $this->school_name = null;
        }else {
            $this->school_id = null;
            $this->school_name = $id;
        }
    }

}
