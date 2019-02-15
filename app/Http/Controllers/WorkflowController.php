<?php

namespace App\Http\Controllers;

use App\Workflow;
use Illuminate\Http\Request;

class WorkflowController extends CuratorialController
{

    public function protectedMethods(){
        return ['showByPermission'];
    }
    public function showAll()
    {
        
        $workflows = Workflow::all();
        foreach($workflows as $w){
            if($w->id == Workflow::REVISION){
                if(!$this->currentNetwork()->useRevisor()){
                    $w->enabled = false;
                }else{
                    $w->enabled = true;
                }
            }else{
                $w->enabled = true;
            }
        }

        return $this->responseOK($workflows);
    }

    public function showByPermission(){
        $ids = $this->curatorialSupervisor->workflowIds();
        return $this->responseOK(Workflow::whereIn('id',$ids)->get());
    }
}