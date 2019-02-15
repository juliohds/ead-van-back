<?php

namespace App\Http\Controllers;

use App\Network;
use App\StateNetwork;
use App\NetworkConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateNetworkController extends Controller
{
    public function showAll() {
        return response()->json(StateNetwork::with('state')->with('network')->get());
    }

    public function saveAll(){
       
       foreach($this->input() as $item){
            $this->save($item);
       }
       return response()->json("salvo com sucesso!");             
    }

    public function save($obj = null){
        if($obj != null){
            $sn = new StateNetwork;
            $sn->network_id = $obj["network_id"];
            $sn->state_id = $obj["state_id"];
            $sn->save();
        }
    }

    public function remove($idStateNetWork){
        $sn = StateNetwork::find($idStateNetWork);
        if($sn->delete()){
            return response($idStateNetWork,200);
        }
        return response($idStateNetWork,500);
    }
}
