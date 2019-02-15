<?php

namespace App;


class CurateAux extends Entity
{
    protected $table = 'curate_aux';

    public function main()
    {
        return $this->belongsTo('App\MainObject');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

