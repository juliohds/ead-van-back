<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Network;
use App\NetworkUser;

class Controller extends BaseController
{
    protected $request;
    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function responseOK($entity){
        return response()->json($entity,Response::HTTP_OK);
    }
    protected function responseOKDelete(){
        return response()->json(['message'=> "Deletedo com sucesso"],Response::HTTP_OK);
    }
    protected function responseCreated($entity){
        return response()->json($entity,Response::HTTP_CREATED);
    }

    public function validation($class){
        $this->validate($this->request,$class::$validate);
    }

    public function currentUserID(){
        if(!$this->request->auth){
            return null;
        }
        return $this->request->auth->id;
    }
    public function currentNetworkID(){
        if(!$this->request->currentNetwork){
            return null;
        }
        return $this->request->currentNetwork->id;
    }
    public function currentNetwork(){
        return $this->request->currentNetwork;
    }
    public function currentRoleID(){
        $role = $this->request->auth->networkUser($this->currentNetworkID());
        return $role->role_id;
    }
    public function currentNetworkUserID(){
        
    }
    public function input($option = null){
        return $this->request->input($option);
    }
}
