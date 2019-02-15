<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MainObject;
use App\Oda;
use App\Collaborate;
use App\TargetObject;
use App\NetworkUser;
use App\NetworkObject;
use App\Workflow;


use App\Services\ServiceCreateOdaUtil;


class CollaborateController extends Controller
{

    public function deleteODa($idNetwork, $id){

        $nu_id = NetworkUser::where('user_id', $this->currentUserID())->where('network_id',$this->currentNetworkID())->pluck('id')->first();

        try {

            $mo = MainObject::where('target_object_id',$id)->get();
            $oda = Oda::find($mo[0]->oda_id);
            $target = TargetObject::find($id);
            $no = NetworkObject::where('main_object_id', $mo[0]->id)->where('network_id', $this->currentNetworkID())->first();
            $collaborate = Collaborate::where('network_user_id',$nu_id)->where('target_object_id',$id)->get();

            //todo delete facet options

            $no->delete();
            $mo[0]->delete();
            $collaborate[0]->delete();
            $target->delete();
            $oda->delete();

        }catch(Exception $e){
            throw new \App\Exceptions\BadRequestException;
        }

        return $this->responseOK("deletado com sucesso");

    }

    public function savePlan(Request $request, $idNetwork){

        $sc = new ServiceCreateOdaUtil;
        $new_oda = $sc->createNewOda($this->input("main_object.oda_type"), $this->input("oda"));
        return $new_oda;

    }

    public function saveOda(Request $request, $idNetwork)
    {
        $this->validate($request, [
            'main_object' => 'required',
            'oda' => 'required',
            'facet_option_ids' => 'required'
        ]);

        try {

            $target = new TargetObject;
            $target->user_id = $this->currentUserID();
            $target->network_id = $this->currentNetworkID();
            $target->save();

            $nu_id = NetworkUser::where('user_id', $this->currentUserID())->where('network_id',$this->currentNetworkID())->pluck('id')->first();

            $collaborate = new Collaborate;
            $collaborate->network_user_id = $nu_id;
            $collaborate->target_object_id = $target->id;
            $collaborate->save();

            $main = new MainObject;
            $main->fill($this->input("main_object"));

            $sc = new ServiceCreateOdaUtil;
            $oda = $sc->createNewOda($this->input("main_object.oda_type"), $this->input("oda"));
            $oda->save();

            $main->oda()->associate($oda);
            $main->target()->associate($target);

            $main->setFacetOptionIds($this->input("facet_option_ids"));

            $main->save();

            $workflowId = Workflow::SUGGESTED;

            $no = new NetworkObject([
                'network_id' => $this->currentNetworkID(),
                'main_object_id' => $main->id,
                'workflow_id' => $workflowId
            ]);

            $no->save();

            return $this->responseCreated($no->toView());

        }catch(Exception $e){
            throw new \App\Exceptions\BadRequestException;
        }

    }


}