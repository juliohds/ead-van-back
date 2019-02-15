<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MainObject;
use App\UserList;
use App\ItemList;
use App\FavorityTargetObject;
use App\NetworkUser;
use App\Helpers\SlugHelper;
use App\ListSlug;

class UserListController extends Controller
{

    public function showAll($idNetwork){

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }
        
        $ul = UserList::whereIn('network_user_id', $nu_id)->with('networkUser.user.person')->withCount('items')->with('listSlug')->paginate();
    
        return response()->json($ul);
    }

    public function qtdUserList($idNetwork){

        $nu_id = NetworkUser::where('network_id', $idNetwork)
                                ->pluck('id')->toArray();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $UserList = UserList::whereIn('network_user_id', $nu_id)
                            ->count();

        return response()->json($UserList);
    }



    public function showUserList(Request $request, $idNetwork)
    {
        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $ul = UserList::where('network_user_id', $nu_id)->with('items')->with('listSlug')->get();

        return response()->json($ul);

    }

    public function create(Request $request, $idNetwork)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $user_list = new UserList;
        $user_list->fill($this->input());
        $user_list->network_user_id = $nu_id;
        $user_list->save();

        $il = new ItemList;
        $il->user_list_id = $user_list->id;
        $il->save();
        
        $lg = $this->saveListSlug($user_list->id, $user_list->title);

        $user_list->slug = $lg;
        $user_list->item_list = $il;

        return response()->json($user_list);

    }

    public function saveListSlug($user_list_id, $user_list_title){

        $ls = new ListSlug;
        $sh = new SlugHelper;
        $ls->user_list_id = $user_list_id;
        $ls->url = $sh->UrlAmigavel($user_list_title." ".$user_list_id);
        $ls->save();

        return $ls;

    }

    public function addOda(Request $request, $idNetwork, $id)
    {
        $this->validate($request, [
            'target_object_id' => 'required'
        ]);

        $il = ItemList::where('user_list_id', $id)
                        ->where('target_object_id',$request->target_object_id)
                        ->get()->first();

        if($il != null) {
            return response()->json(array('message'=>'Oda Já cadastrada!'));
        }

        $il = new ItemList([
            'target_object_id'=>$request->target_object_id,
            'user_list_id'=>$id
        ]);

        $il->save();

        return response()->json(array('message'=>'Oda inserida na lista!'));
    }

    public function removeOda(Request $request, $idNetwork, $id)
    {
        $il = ItemList::where('user_list_id', $id)
                        ->where('target_object_id',$request->target_object_id)
                        ->get()->first();
        $il->delete();

        return response()->json(array('message'=>'Oda removida da lista!'));
    }

    public function showFavority(Request $request, $idNetwork, $id){

        $il = ItemList::where('user_list_id', $id)->pluck('target_object_id')->toArray();

        $favority_itens = FavorityTargetObject::whereIn('target_object_id', $il)->where('picked', true)->pluck('target_object_id')->toArray();

        try{
            $favoritys_odas = MainObject::whereIn('target_object_id', $favority_itens)->get();
        }catch(\Exception $e){
            throw new \App\Exceptions\NotFoundException;
        }

        return response()->json($favoritys_odas);

    }

    public function showListAndTotalFavority(Request $request, $idNetwork){

        $nu_id = NetworkUser::where('user_id', $request->auth->id)->where('network_id', $idNetwork)
                                ->pluck('id')->first();

        if($nu_id == null){
            throw new \App\Exceptions\NotFoundException;
        }

        $il = UserList::where('network_user_id', $nu_id)->with('items')->withCount('items')->with('listSlug')->get();

        return response()->json($il);
    }

    public function edit(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_public' => 'required'
        ]);

        $user_list = UserList::find($id);

        $old_ul_title = $user_list->title;

        $user_list->setAttribute('title', $request->title);
        $user_list->setAttribute('description', $request->description);
        $user_list->setAttribute('is_public', $request->is_public);
        $user_list->save();

        if($old_ul_title != $request->title){
           $ls = $this->saveListSlug($id, $user_list->title);
           $user_list->slug = $lg;
        }

        return response()->json($user_list);
    }

    public function delete($id)
    {
        $iul = ItemList::where('user_list_id', $id)->first();
        if($iul != null){
            $iul->delete();
        }
        $user_list = UserList::find($id);
        $user_list->delete();

        return response()->json('Deletado com sucesso!');
    }

    public function showListHome(){

        $nu_id = NetworkUser::where('network_id', $this->request->currentNetwork->id)->pluck('id')->toArray();
        $ul_public_id = UserList::where('is_public', true)->whereIn('network_user_id',$nu_id)->where('deleted_at', null)->pluck('id')->toArray();
        $ul_with_tid = ItemList::whereIn('user_list_id', $ul_public_id)->whereNotNull('target_object_id')->pluck('user_list_id')->toArray();
        $ul_ids = ListSlug::whereIn('user_list_id', $ul_with_tid)->pluck('user_list_id')->toArray();

        $max = UserList::whereIn('id',$ul_ids)->count();
        
        if($max < 1){
            return response()->json('error numero de listas inferior a 1');
        }
        
        $rand_keys = array_rand($ul_ids, 4);
        $rand_value = [$ul_ids[$rand_keys[0]],$ul_ids[$rand_keys[1]],$ul_ids[$rand_keys[2]],$ul_ids[$rand_keys[3]]];
        
        $res_ul = UserList::whereIn('id',$rand_value)->with('items')->get();
        //return response()->json($res_ul);
        $idL_idT = [];
        foreach($res_ul as $item){
            $aux["user_list_id"] = $item->id;
            $aux["tg_id_and_picture"] = [];
            foreach($item->items as $it){
                $img = MainObject::where('target_object_id', $it->target_object_id)->pluck('picture')->first(); 
                array_push($aux["tg_id_and_picture"], $img);
            }
            $url = ListSlug::where('user_list_id', $it->user_list_id)->pluck('url')->first(); 
            $aux["slug"] = [];
            array_push($aux["slug"], $url);
            array_filter($aux["tg_id_and_picture"]);
            array_push($idL_idT, $aux);
            $aux = [];
        }
        
        $obj_ul = UserList::whereIn('id',$rand_value)->get();

        for ($i=0; $i < count($obj_ul); $i++) { 
            
            $obj_ul[$i]->items = $idL_idT[$i];
        }

       return response()->json($obj_ul);
    }

    public function showListPublicBySlug($idNetwork, $slug){

        $id_sl = ListSlug::where('url', $slug)->pluck('user_list_id')->first();

        $result_ul = UserList::where('id',$id_sl)->with('networkUser.user.person')->get();

        try{
            $id_target_il = ItemList::where('user_list_id', $id_sl)->pluck('target_object_id')->toArray();
            $mo = MainObject::whereIn('target_object_id', $id_target_il)->with('target')->with('oda')->with('facetOptions')->get();
        }catch(\Exception $e){
            throw new \App\Exceptions\NotFoundException;
        }

        $result_ul[0]->itens=$mo;

        return response()->json($result_ul);

    }

}