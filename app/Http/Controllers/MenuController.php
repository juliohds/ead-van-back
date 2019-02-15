<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Menu;

class MenuController extends Controller
{
     
    public function update($idNetwork, $id){
        
        $menu = Menu::find($id);
        $menu->fill($this->input());
        $menu->update();
                
        return response()->json($menu);
    }

    public function delete($idNetwork, $id){
        
        $menu = Menu::find($id);
        $menu->delete();
        return response()->json("deletado");
    }

    public function showAll($idNetwork, $idNC){
        $menu = Menu::where('network_config_id', $idNC)->orderBy('ui_index')->get();
        return response()->json($menu);
    }

    public function showAllById($idNetwork, $idNC, $idMenu){
        $menu = Menu::where('id', $idMenu)->first();
        return response()->json($menu);
    }

    public function insert(Request $request, $idNetwork){
        
        $menu = new Menu;
        $menu->fill($this->input());
        $menu->save();

        return response()->json($menu);
    
    }
}

