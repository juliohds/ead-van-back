<?php

namespace App;

use App\Libs\Elasticsearch;
use App\Workflow;
use App\MainObject;
use App\Helpers\FacetHelper;

class NetworkObject extends EntityIndexable implements Indexable
{
    protected $table = 'network_object';
    protected $other_network_ids = [];

    public static $searchFields = [
        'title' => 'wildcard',
        'description' => 'wildcard',
        'tags' => 'match'
    ];


    public function network()
    {
        return $this->belongsTo('App\Network');
    }
    public function mainObject()
    {
        return $this->belongsTo('App\MainObject');
    }

    public function workflow(){
        return $this->belongsTo('App\Workflow');
    }

    public function save(array $options = []){
        parent::save($options);
        $es = Elasticsearch::Instance();
        $method = ($this->workflow_id != Workflow::ARCHIVED) ? 'indexItem' : 'deleteItem';
        $es->$method($this,["networkId" => $this->network_id]);
    }

    public function update(array $attributes = [],array $options = []){
        //print($this->id);
        // print($this->main_object_id);
        //die();
        parent::save($options);
        $es = Elasticsearch::Instance();
        $method = ($this->workflow_id != Workflow::ARCHIVED) ? 'indexItem' : 'deleteItem';
        $es->indexItem($this,["networkId" => $this->network_id]);
    }

    public function archieve(){
        $this->workflow_id = Workflow::ARCHIVED;
        $this->save();
    }

    public function toView(){
        $obj = $this->mainObject;
        $obj->target->user;
        $obj->workflow_id = $this->workflow_id;
        $obj->oda;
        $fh = new FacetHelper;
        $fh->mergedFacets($obj,$this->network_id);
        return $obj;
    }

    public function scopeByNetwork($query,$idMain,$idNetwork)
    {
        return $query->where('network_id', $idNetwork)->where('main_object_id',$idMain);

    }

    public static function allNetwork($idNetwork,$perPage = null,$page = null,$title = null)
    {
        $perPage = $perPage ? $perPage : 10;
        $page = $page ? $page : 1;
        $query = NetworkObject::where('network_id', $idNetwork)
            ->where('workflow_id','<>',Workflow::ARCHIVED);
        if($title){
            //Refactoring needed to get best approach.
            //Maybe we should use elastisearch for strings searchs
            $ids = $query->get()->pluck('main_object_id');
            $titleIds = MainObject::whereIn('id',$ids)
                ->where('title','like',"%$title%")
                ->get()->pluck('id');
            $query = NetworkObject::where('network_id', $idNetwork)
                ->where('workflow_id','<>',Workflow::ARCHIVED)->whereIn('main_object_id',$titleIds);
        }
        $all = $query->paginate($perPage,['*'],'page',$page);
        foreach($all as $it){
            $it->toView();
        }
        return $all;

    }

    public static function othersNetwork($idNetwork,$perPage = null,$page = null,$title = null){
        $perPage = $perPage ? $perPage : 10;
        $page = $page ? $page : 1;
        $ids =  NetworkObject::sameVersionIds($idNetwork);
        $query = NetworkObject::whereNotIn('id', $ids);
        if($title){
            //Refactoring needed to get best approach.
            //Maybe we should use elastisearch for strings searchs
            $ids = $query->get()->pluck('main_object_id');
            $titleIds = MainObject::whereIn('id',$ids)
                ->where('title','like',"%$title%")
                ->get()->pluck('id');
            $query = NetworkObject::whereNotIn('id', $ids)->whereIn('main_object_id',$titleIds);
        }
        $all = $query->paginate($perPage,['*'],'page',$page);
        foreach($all as $it){
            $it->toView();
        }
        return $all;

    }

    private static function sameVersionIds($idNetwork){
        $ids = NetworkObject::where('network_id', $idNetwork)->get()->pluck('main_object_id');
        $targetIds = MainObject::whereIn('id',$ids)->get()->pluck('target_object_id')->toArray();
        $targetIds = array_unique($targetIds);
        $ids = MainObject::whereIn('target_object_id',$targetIds)->get()->pluck('id');


        $nis = NetworkObject::whereIn('main_object_id',$ids)->get()->pluck('id');
        return $nis;
    }

    public function getIndexable(){
        if($this->main_object_id != $this->mainObject->id){
            $this->mainObject = MainObject::find($this->main_object_id);
        }
        $indexable = clone $this->mainObject;
        $indexable->bncc_tags_array = explode(',',$indexable->bncc_tags);
        $indexable->oda;
        $indexable->workflow_id = $this->workflow_id;
        $indexable->network_id = $this->network_id;
        $indexable->other_network_ids = array_merge([],$this->networksUsingThisObject());
        return $indexable->toJson();
    }

    public function getMapping() {
        return $this->mainObject->getMapping();
    }

    public function networksUsingThisObject(){
        try{
            $ids = $this->mainObject->target->versions()->get()->pluck('id');
            $nis = NetworkObject::whereIn('main_object_id',$ids)->get()->pluck('network_id')->toArray();
            return array_unique($nis);
        }catch(\Exception $e){
            return [];
        }
    }
}
