<?php

namespace App\Http\Controllers;

use App\NetworkHomeFacet;

class NetworkHomeFacetController extends Controller
{
    public function showAll($idNetwork, $idnc)
    {
        $nHF = NetworkHomeFacet::where('network_config_id', $idnc)->with('NetworkFacet')->orderBy('ui_index')->get();
        $aux = 1;
        if($nHF == null || count($nHF) == 0){
            for ($i=0; $i < 3; $i++) { 
                $nHF1 = new NetworkHomeFacet;
                $nHF1->network_config_id = $idnc;
                $nHF1->network_facet_id = $aux;
                $nHF1->ui_index = $i;
                $nHF1->save();
                $aux++;
            }
            $nHF = NetworkHomeFacet::where('network_config_id', $idnc)->with('NetworkFacet')->orderBy('ui_index')->get();
        }
        return response()->json($nHF);
    }
    
    public function update($idNetwork, $idnc, $idhf)
    {
        $nHF = NetworkHomeFacet::where('network_config_id', $idnc)->where('ui_index', $idhf)->first();
        $nHF->network_config_id = $this->input('network_config_id');
        $nHF->network_facet_id = $this->input('network_facet_id');
        $nHF->ui_index = $this->input('ui_index');
        $nHF->update();
        return response()->json($nHF);
    }

    public function insert($idNetwork, $idnc)
    {
        $nHF = new NetworkHomeFacet;
        $nHF->network_config_id = $this->input('network_config_id');
        $nHF->network_facet_id = $this->input('network_facet_id');
        $nHF->ui_index = $this->input('ui_index');
        $nHF->save();
        return response()->json($nHF);
    
    }
}