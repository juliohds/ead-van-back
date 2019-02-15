<?php

namespace App\Http\Controllers;

use App\Profile;
use App\User;
use App\NetworkUser;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    public function update(Request $request, $idNetwork, $id){

        $nu = NetworkUser::where('user_id', $id)->where('network_id', $idNetwork)->first();   
        $nu->role_id = $request->input('role_id');
        $nu->update();

        return response()->json($nu);
    }
    
    public function showAll(Request $request, $idNetwork){
        
        $nu = $request->auth->networkUser($request->currentNetwork->id);
        
        if($nu->role->tag == "admin"){
            $app_role = Role::all();    
        }
        else if($nu->role->tag == "net admin"){
            $app_role = Role::where('tag', '<>', '%admin%')->get();
        }
        else{
            $app_role = [];
        }
        return response()->json($app_role);
    }

}