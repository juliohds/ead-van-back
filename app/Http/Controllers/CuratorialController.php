<?php

namespace App\Http\Controllers;

use App\NetworkObject;
use App\Workflow;
use App\Security\CuratorialSupervisor;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Route;

abstract class CuratorialController extends Controller
{
    protected $curatorialSupervisor = null;

    public function __construct(Request $request) {
        parent::__construct($request);
        $id = $this->getParam('id');
        
        if(in_array($this->currentControllerMethod(),$this->protectedMethods())){
            if(!$this->currentNetworkID() || !$this->currentUserID()){
                throw new BadRequestException;
            }
            $workflowId = $this->input('workflow_id');

            $method = strtolower($request->getMethod());
            if($method == 'delete' || $method = 'get' ){
                $workflowId = Workflow::NEW;
            }
            
            $currentWorkflowId = null;
            if($id){
                $no = NetworkObject::byNetwork($id,$this->currentNetworkID())->first();
                if(!$no){
                    throw new NotFoundException;
                }
                $currentWorkflowId = $no->workflow_id;
            }else{
                $currentWorkflowId = Workflow::NEW;
            }

            $this->curatorialSupervisor = new CuratorialSupervisor(
                $currentWorkflowId,
                $workflowId,
                $this->request->currentNetwork,
                $this->currentRoleID()  
            );
        }
    }

    abstract public function protectedMethods();

    private function currentControllerMethod(){
        try{
            $action = $this->request->route();
            $it = ($action[1]['uses']);
            return explode('@',$it)[1];
        }catch(\Exception $e){
            return null;
        }
    }
    private function getParam($param){
        try{
            $action = $this->request->route();
            $it = ($action[2][$param]);
            return $it;
        }catch(\Exception $e){
            return null;
        }
        
    }
}