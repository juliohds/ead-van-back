<?php

namespace App\Http\Controllers;

use App\Facet;
use App\FacetOption;
use App\NetworkFacetOption;
use App\NetworkFacet;

class AcademicController extends Controller

{

   public function subjects($idNetwork){
    $facet_id = Facet::where('type', 'discipline')->pluck('id')->first();
    $nf_id = NetworkFacet::where('network_id', $idNetwork)->where('facet_id', $facet_id)->pluck('id')->first();
    $fo = NetworkFacetOption::where('network_facet_id', $nf_id)->get();
    return response()->json($fo);
   }

   public function grades($idNetwork){
    $facet_id = Facet::where('type', 'year')->pluck('id')->first();
    $nf_id = NetworkFacet::where('network_id', $idNetwork)->where('facet_id', $facet_id)->pluck('id')->first();
    $fo = NetworkFacetOption::where('network_facet_id', $nf_id)->get();
    return response()->json($fo);
   }

}