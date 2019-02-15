<?php

namespace App\Http\Controllers;

use App\NetworkFacet;
use App\NetworkFacetOption;
use App\FacetOption;
use Illuminate\Http\Request;

class networkFacetController extends Controller
{                   
    public function getFacetOptionsById($id)
    {
        $limit = $this->input("limit");
        
        if($limit != null){
            $option = NetworkFacet::with('options.facetOption')->where('id', $id)->get();
            $options = $option[0]->options->toArray();
            $options = array_splice($options, 0, $limit);
            $option[0]->options_limit = $options;
            
        }else{
            $option = NetworkFacet::with('options.facetOption')->where('id', $id)->get();
        }

        if($option == null){    
            throw new \App\Exceptions\NotFoundException; 
        }
        
        return response()->json($option);
    }

    public function showAll($id){
        $nf = NetworkFacet::where('network_id', $id)->get();
        return response()->json($nf);
    }

}