<?php

namespace App;

class Card extends Entity
{

    protected $table = 'card';

    public function tabs()
    {
        return $this->belongsTo('App\Tab');
    }
}
