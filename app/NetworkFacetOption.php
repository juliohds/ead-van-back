<?php

namespace App;


class NetworkFacetOption extends Entity
{
    protected $table = 'network_facet_option';
    protected $appends = ['network_title','network_picture'];
    protected $hidden = ['title','networkFacet','facetOption','created_at',
        'updated_at','deleted_at','picture'];
    protected $fillable = ['picture','title','ui_index','enabled'];

    public function networkFacet()
    {
        return $this->belongsTo('App\NetworkFacet');
    }

    public function facetOption()
    {
        return $this->belongsTo('App\FacetOption');
    }
    public function getNetworkTitleAttribute(){
        return $this->title != null ? $this->title : $this->facetOption->title;
    }
    public function getNetworkPictureAttribute(){
        return $this->picture != null ? $this->picture : $this->facetOption->picture;
    }

}
