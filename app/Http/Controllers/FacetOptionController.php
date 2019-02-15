<?php

namespace App\Http\Controllers;

use App\Facet;
use App\FacetOption;
use App\NetworkFacetOption;
use App\NetworkFacet;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;

class FacetOptionController extends Controller
{
    public function showAll(Request $request, $id)
    {
        $limit = $request->input("limit");

        if($limit != null){
            $option = Facet::with('options.facet')->where('id', $id)->limit($limit)->get();
        }else{
            $option = Facet::with('options.facet')->where('id', $id)->get();
        }

        if($option == null){
            throw new NotFoundException;
        }

        return response()->json($option);
    }

    public function create($idFacet){
        $this->validation(FacetOption::class);
        $networkFacet = NetworkFacet::where('network_id',$this->currentNetworkID())
            ->where('facet_id',$idFacet)->first();
        if(!$networkFacet){
            throw new NotFoundException;
        }

        $fo = new FacetOption;
        $fo->fill($this->input());
        $fo->facet_id = $idFacet;
        $fo->save();

        $nfo = new NetworkFacetOption;
        $nfo->fill($this->input());
        $nfo->facet_option_id = $fo->id;
        $nfo->network_facet_id = $networkFacet->id;
        $nfo->save();

        return $this->responseCreated($nfo);

    }

    public function updateAll(){

        $id_ui = $this->input();
        foreach($id_ui as $item){
            $networkFacet = NetworkFacetOption::find($item["id"]);
            $networkFacet->ui_index = $item["ui_index"];
            $networkFacet->update();
        }
        return $this->responseOK(array("message"=>"FacetOptions Atualizados com sucesso!"));

    }

    public function update($idFacet, $id){
        $networkFacetOption = NetworkFacetOption::find($id);
        if(!$networkFacetOption || $networkFacetOption->networkFacet->facet_id != $idFacet){
            throw new NotFoundException;
        }
        $networkFacetOption->fill($this->input());
        $networkFacetOption->update();

        return $this->responseOK($networkFacetOption);
    }

}