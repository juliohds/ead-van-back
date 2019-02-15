<?php

namespace App;


class DevelopmentResource extends Oda implements Versionable
{
    protected $table = 'development_resource';
    protected $hidden = array('created_at', 'updated_at');
    protected $fillable = ['url'];
    
    public function comparableFields(){
        return ['url'];
    }

}
