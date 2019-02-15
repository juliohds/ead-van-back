<?php
namespace App;
class State extends Entity
{
    protected $fillable = ['id', 'name', 'uf', 'ibge_uf'];
    protected $table = "state";
    
    public function cities(){
        return $this->hasMany(City::class);
    }

}