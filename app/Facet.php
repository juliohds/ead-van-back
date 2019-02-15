<?php

namespace App;


class Facet extends Entity
{
    protected $table = 'facet';
    protected $hidden = array('created_at', 'updated_at');
    protected $fillable = [
        'type',
        'title',
        'help_text',
    ];
    public static $validate = [
        'title' => 'required',
        'facet_type' => 'required',
        'required' => 'required',
        'enabled' => 'required',
        'suggestion_enabled' => 'required',
    ];

    public function options()
    {
        return $this->hasMany('App\FacetOption');
    }

    public function networks()
    {
        return $this->belongsToMany('App\Network', 'network_facet','facet_id', 'network_id' );
    }

    public function setFacetType($facetType)
    {
        switch ($facetType) {
            case 'course':
                $this->facet_type_id = FacetType::COURSE;
                break;
            default:
                $this->facet_type_id = FacetType::ODA;
        }
    }
    public function getFacetType(){
        return FacetType::defaultValue($this->facet_type_id);
    }
    

}
