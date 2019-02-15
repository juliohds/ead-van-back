<?php

namespace App;

class ModuloTab extends Entity
{

    protected $table = 'modulo_tab';

    public function rede()
    {
        return $this->belongsTo('App\Network');
    }

}
