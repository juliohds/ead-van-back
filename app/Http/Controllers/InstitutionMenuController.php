<?php

namespace App\Http\Controllers;

use App\InstitutionMenu;
use Illuminate\Http\Request;

class InstitutionMenuController extends Controller
{
    public function show($idNetwork, $id)
    {
        $im = InstitutionMenu::where('network_config_id', $id)->orderBy('id')->get();
        return $this->responseOK($im);

    }

    public function showById($idNetwork, $id, $idInst)
    {
        $im = InstitutionMenu::where('id', $idInst)->first();
        return $this->responseOK($im);
    }

    public function update($idNetwork, $id, $idInst)
    {
        $im = InstitutionMenu::find($idInst);
        $im->url = $this->input('url');
        $im->image =  $this->input('image');
        $im->update();
        return $this->responseOK($im);
    }

    public function create($idNetwork, $id){
        
        $im = new InstitutionMenu;
        $im->url = $this->input('url');
        $im->image =  $this->input('image');
        $im->network_config_id =  $this->input('network_config_id');
        $im->save();

        return $this->responseCreated($im);
    }

    public function delete($idNetwork, $id, $idInst){
        
        $im = InstitutionMenu::find($idInst);
        $im->delete();
        return $this->responseOK("ok");
    }
    
}