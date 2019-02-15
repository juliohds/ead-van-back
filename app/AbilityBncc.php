<?php

namespace App;

class AbilityBncc extends Entity
{
    protected $table = "ability_bncc";
    protected $hidden = ['created_at','updated_at','id','start_year','end_year','component_bncc_id'];
    
    public function cycle()
    {
        return $this->belongsTo(CycleBncc::class);
    }
}