<?php

namespace App;

class ComponentBncc extends Entity
{
    protected $table = "component_bncc";
    protected $hidden = ['created_at','updated_at'];
    public function cycle()
    {
        return $this->belongsTo(CycleBncc::class);
    }
}