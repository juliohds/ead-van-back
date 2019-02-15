<?php

namespace App\Http\Controllers;

use App\NetworkCustomPage;

class CustomPageController extends Controller

{

   public function show($slug){
        $customPage = NetworkCustomPage::where([['network_id', $this->currentNetworkID()],['slug',$slug]])->first();
        if(!$customPage) {
            throw new \App\Exceptions\NotFoundException;
        }
        return $this->responseOK($customPage);
   }

   public function showAll(){
    $customPages = NetworkCustomPage::where('network_id', $this->currentNetworkID())->orderBy('created_at', 'desc')->get()->toArray();
    if(!$customPages) {
        throw new \App\Exceptions\NotFoundException;
    }
    return $this->responseOK($customPages);
}

   public function create(){
        $customPage = new NetworkCustomPage;
        $customPage->fill($this->input());
        $customPage->network_id = $this->currentNetworkID();
        $strSlug = str_slug($customPage->title, '-');
        $existSlug = NetworkCustomPage::where([['network_id', $this->currentNetworkID()],['slug','LIKE', ('%'.$strSlug.'%')]])->count();
        if($existSlug == 0) {
            $customPage->slug = $strSlug;
            $customPage->save();
            return $this->responseOK($customPage);
        }else{
            $strSlug = str_slug($customPage->title." ".$existSlug, '-');
            $customPage->slug = $strSlug;
            $customPage->save();
            return $this->responseOK($customPage);
        }
    }

    public function update($slug){
        $customPage = NetworkCustomPage::where([['network_id', $this->currentNetworkID()],['slug',$slug]])->first();
        if(!$customPage){
            throw new NotFoundException;
        }
        $customPage->fill($this->input());
        $customPage->update();
        return $this->responseOK($customPage);
    }

    public function delete($slug){
        $customPage = NetworkCustomPage::where([['network_id', $this->currentNetworkID()],['slug',$slug]])->first();
        if(!$customPage){
            throw new NotFoundException;
        }
        $customPage->delete();
        return $this->responseOKDelete();
    }

}