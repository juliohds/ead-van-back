<?php

namespace App;


class Course extends Oda implements Versionable
{
    protected $table = 'course';
    protected $hidden = array('created_at', 'updated_at');
    
    protected $fillable = ['url','total_hours','goal'];
    
    public function comparableFields(){
        return ['url','total_hours','goal'];
    }

}
