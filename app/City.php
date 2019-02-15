<?php

namespace App;

class City extends Entity
{
    protected $fillable = ['id', 'name'];
    protected $table = "city";
    
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}