<?php

namespace App;


class NetworkHomeFacet extends Entity
{
    protected $table = 'network_home_facet';
    // protected $hidden = array('created_at', 'updated_at');

    public function network()
    {
        return $this->belongsTo('App\NetworkConfig');
    }


    public function NetworkFacet()
    {
        return $this->belongsTo('App\NetworkFacet');
    }

}
