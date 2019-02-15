<?php

namespace App;


class FacetOption extends Entity
{
    protected $table = 'facet_option';
    
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    protected $fillable = ['title','picture'];
    
    public static $validate = [
        'title' => 'required',
    ];

    public function facet()
    {
        return $this->belongsTo('App\Facet');
    }

    public function objects() {
        return $this->belongsToMany('App\MainObject','object_facet_option', 'main_object_id', 'facet_option_id');
    }

}

