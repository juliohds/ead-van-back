<?php

namespace App;

class Tab extends Entity
{

    protected $table = 'tab';

    public function moduloTab()
    {
        return $this->belongsTo('App\ModuloTab');
    }
}
