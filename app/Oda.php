<?php

namespace App;


class Oda extends DigitalResource implements Versionable
{
    protected $table = 'oda';
    protected $fillable = ['url'];
    
    public function comparableFields(){
        return ['url'];
    }

}
