<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModuloTab;
use App\Tab;
use App\Card;

class ModuloTabController extends Controller
{

    public function update($idNetwork, $id){

        $ModuloTab = ModuloTab::find($id);
        $ModuloTab->title = $this->input('title');
        $ModuloTab->network_id = $this->input('network_id');
        $ModuloTab->update();

        foreach($this->input('tab') as $tab){
            $Tab = Tab::find($tab['id']);
            $Tab->title = $tab['title'];
            $Tab->modulo_tab_id = $ModuloTab->id;
            $Tab->update();

            foreach($tab['card'] as $card){
                $Card = Card::find($card["id"]);
                $Card->title = $card["title"];
                $Card->description = $card["description"];
                $Card->url = $card["url"];
                $Card->img = $card["img"];
                $Card->tab_id = $Tab->id;
                $Card->update();
            }
        }

        return response()->json($this->show($idNetwork,$ModuloTab->id));

    }

    public function delete($idNetwork, $id){

        $ModuloTab = ModuloTab::find($id);
        $ModuloTab->fill($this->input());
        $ModuloTab->delete();
        return response()->json("Deletado");

    }
    
    public function showAll($idNetwork){
        
        $ModuloTab = ModuloTab::where('network_id', $idNetwork)->first();
        
        if($ModuloTab == null){
            return response()->json("nenhum modulo nesta rede");
        }

        $Tab = Tab::where('modulo_tab_id', $ModuloTab->id)->get()->toArray();
        $ModuloTab["tab"] = $Tab;

        $aux = [];
        foreach($ModuloTab["tab"] as $tab){ 
        
            $cards = Card::where('tab_id', $tab['id'])->get()->toArray();
            $tab["card"] = $cards;

            $aux[] = $tab;
        }

        $ModuloTab["tab"] = $aux;

        return response()->json($ModuloTab);
    }

    public function show($idNetwork, $id){
        
        $ModuloTab = ModuloTab::find($id);
        $Tab = Tab::where('modulo_tab_id', $id)->get()->toArray();
        $ModuloTab["tab"] = $Tab;


        $aux = [];
        foreach($ModuloTab["tab"] as $tab){ 
        
            $cards = Card::where('tab_id', $tab['id'])->get()->toArray();
            $tab["card"] = $cards;

            $aux[] = $tab;
        }

        $ModuloTab["tab"] = $aux;

        return response()->json($ModuloTab);
    }

    public function insert($idNetwork){

        $ModuloTab = new ModuloTab;
        $ModuloTab->title = $this->input('title');
        $ModuloTab->network_id = $this->input('network_id');
        $ModuloTab->save();

        foreach($this->input('tab') as $tab){
            $Tab = new Tab;
            $Tab->title = $tab['title'];
            $Tab->modulo_tab_id = $ModuloTab->id;
            $Tab->save();

            foreach($tab['card'] as $card){
                $Card = new Card;
                $Card->title = $card["title"];
                $Card->description = $card["description"];
                $Card->url = $card["url"];
                $Card->img = $card["img"];
                $Card->tab_id = $Tab->id;
                $Card->save();
            }
        }

        return response()->json($this->show($idNetwork,$ModuloTab->id));
    }
}

