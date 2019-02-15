<?php

namespace App;

class Comment extends Entity
{
    protected $table = 'comment';
    
    public function networkUser()
    {
        return $this->belongsTo('App\NetworkUser');
    }

    public function person()
    {
        return $this->belongsTo('App\Person');
    }
}
