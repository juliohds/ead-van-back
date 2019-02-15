<?php

namespace App\Http\Controllers;

use App\FavorityTargetObject;
use App\MainObject;
use App\NetworkUser;
use App\Helpers\FacetHelper;

use Illuminate\Http\Request;

class FavorityTargetObjectController extends Controller
{
    public function updateFavorityTargetObject(Request $request, $idTargetObject, $idNetwork)
    {
        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $favority = FavorityTargetObject::where('network_user_id', $nu_id)
                                        ->where('target_object_id', $idTargetObject)->first();

        if($favority == null){
            $favority = new FavorityTargetObject;
            $favority->setAttribute('picked',true);
            $favority->setAttribute('network_user_id', $nu_id);
            $favority->setAttribute('target_object_id', $idTargetObject);
        }
        elseif($favority->picked){
            $favority->setAttribute('picked',false);
        }else{
            $favority->setAttribute('picked',true);
        }

        $favority->save();
        return response()->json($favority);
    }

    public function showFavorityObjectByOdaId(Request $request, $idNetwork, $id_target_oda){

        $nu_id = NetworkUser::where('user_id', $request->auth->id)->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }
        
        $id_favority_odas = FavorityTargetObject::where('target_object_id', $id_target_oda)
                                                ->where('network_user_id', $nu_id)
                                                ->pluck('picked')->first();

        return response()->json(($id_favority_odas == null)? false:$id_favority_odas);

    }

    public function showAllFavorityTargetObject(Request $request, $idNetwork){

        $nu_id = NetworkUser::where('user_id', $request->auth->id)
                              ->where('network_id', $idNetwork)
                              ->pluck('id')
                              ->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $id_favority_odas = FavorityTargetObject::where('picked', true)
                                                ->where('network_user_id', $nu_id)
                                                ->pluck('target_object_id')
                                                ->toArray();
                                                
        try{
            $favoritys_odas = MainObject::whereIn('target_object_id', $id_favority_odas)
                                      ->with('oda')->with('target')->get();
                                      return response()->json($favoritys_odas);
        }catch(\Exception $e){
            throw new \App\Exceptions\NotFoundException;
        }

        $fh = new FacetHelper;
        $fh->mergedFacetsToObjectsArray($favoritys_odas, $idNetwork);

        return response()->json($favoritys_odas);

    }

    public function showFavorityTargetObject(Request $request, $idTargetObject, $idNetwork)
    {
        $nu_id = NetworkUser::where('user_id', $request->auth->id)
                              ->where('network_id', $idNetwork)
                              ->pluck('id')
                              ->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $favority = FavorityTargetObject::where('network_user_id', $nu_id)
                                        ->where('target_object_id', $idTargetObject)->first();

        if($favority == null){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($favority);
    }

    public function myFavority(Request $request, $idNetwork){
        
        $nu_id = NetworkUser::where('user_id', $request->auth->id)
                              ->pluck('id')
                              ->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $favority = FavorityTargetObject::where('picked', true)
                                        ->where('network_user_id', $nu_id)
                                        ->pluck('target_object_id')->toArray();

        try{
            $favority_itens = MainObject::whereIn('target_object_id', $favority)->get();
        }catch(\Exception $e){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($favority_itens);

    }    

}

