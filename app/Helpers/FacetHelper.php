<?php

namespace App\Helpers;

use App\NetworkFacet;
use App\NetworkFacetOption;

class FacetHelper {

    public function mergedFacetsToObjectsArray($mainObjects,$networkId){
        foreach($mainObjects as $mainObject){
            $this->mergedFacets($mainObject, $networkId);
        }
    }

    public function mergedFacets($mainObject,$networkId){
        $facetIds = NetworkFacet::where('network_id',$networkId)->pluck('id')->toArray();
        $options = NetworkFacetOption::whereIn('network_facet_id',$facetIds)
            ->whereIn('facet_option_id',$mainObject->facetOptions()->pluck('facet_option_id'))
            ->with('facetOption')->get();
        $facets = [];
        foreach($options as $op){
            $key = $op->networkFacet->network_title;
            if(!array_key_exists($key,$facets)){
                $facets[$key] = [
                    'facet' => $op->networkFacet,
                    'options' => []
                ];
            }
            array_push($facets[$key]['options'], $op);
            
        }
        $objFacets = [];
        foreach($facets as $k => $v){
            $obj = $v['facet'];
            $obj->options = $v['options'];
            array_push($objFacets,$obj);
        }
        $mainObject->facets = $objFacets;
    }
}
