<?php

namespace App\Http\Controllers;

use App\Facet;
use App\FacetType;
use App\NetworkFacet;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;

class FacetController extends Controller
{
    public function showAll()
    {
        $facetType = $this->input('facet_type') ? $this->input('facet_type') : 'oda';
        $networkFacets = $this->request->currentNetwork->networkFacets
            ->where('facet.facet_type_id',FacetType::valueOf($facetType));
        //We must refactor this code.
        //Query result returning array for firsts elements and object to others
        $result = [];
        foreach($networkFacets as $nf){
            array_push($result,$nf);
        }

        return $this->responseOK($result);

    }

    public function showById($idNetwork, $id)
    {
        $networkFacets = $this->request->currentNetwork->networkFacets
            ->where('facet.id', $id);
        $result = [];
        foreach($networkFacets as $nf){
            array_push($result,$nf);
        }
        return $this->responseOK($result);
    }

    public function create(){
        
        $this->validation(Facet::class);
        $facet = new Facet;
        $facet->fill($this->input());
        $facet->setFacetType($this->input('facet_type'));
        $facet->save();
        $networkFacet = new NetworkFacet;
        $networkFacet->fill($this->input());
        $networkFacet->facet_id = $facet->id;
        $networkFacet->network_id = $this->currentNetworkID();
        $networkFacet->save();  

        return $this->responseCreated($networkFacet);
    }

    public function updateAll(){

        $id_ui = $this->input();
        foreach($id_ui as $item){
            $networkFacet = NetworkFacet::find($item["id"]);
            $networkFacet->ui_index = $item["ui_index"];
            $networkFacet->update();
        }
        return $this->responseOK($networkFacet);

    }

    public function update($idNetwork, $id){
        
        $networkFacet = NetworkFacet::find($id);
        
        if(!$networkFacet){
            throw new NotFoundException;
        }

        $networkFacet->fill($this->input());

        if($this->input('help_text')){
            $facet = Facet::find($networkFacet->facet_id);
            $facet->help_text = $this->input('help_text');
            $facet->update();
        }

        $networkFacet->update();  

        return $this->responseOK($networkFacet);
    }
    
}