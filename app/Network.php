<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

class Network extends Entity
{
    use SoftDeletes;
 
    protected $table = 'network';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'url', 'alternate_url', 'available'];

    public function networkObjects(){
        return $this->hasMany('App\NetworkObject');
    }
    public function networkFacets()
    {
        return $this->hasMany('App\NetworkFacet')->with('facet')->with('options')
            ->orderBy('ui_index','asc');
    }

    public function networkUsers(){
        return $this->hasMany('App\NetworkUser');
    }

    public function networkConfig(){
        return $this->belongsTo('App\NetworkConfig')->with('homeFacets');
    }

    public function useRevisor(){
        $config = $this->networkConfig;
        if($config){
            return $config->use_revisor;
        }
        return false;
    }
}
