<?php

namespace App\Libs;
use App\NetworkObject;
class SearchingBuilder {
    private $networkId;
    private $q;
    private $page;
    private $per_page;
    private $sort;
    private $order;
    private $type;
    private $queryOptions;
    private $workflowId;
    
    private function __contruct(){}

    public static function builder(){
        return new SearchingBuilder;
    }
    public  function network($networkId){
        $this->networkId = $networkId;
        return $this;
    }
    public  function q($q){
        $this->q = $q;
        return $this;
    }
    public  function page($page){
        $this->page = $page;
        return $this;
    }
    public  function perPage($perPage){
        $this->perPage = $perPage;
        return $this;
    }
    public  function sort($sort){
        $this->sort = $sort;
        return $this;
    }
    public  function order($order){
        $this->order = $order;
        return $this;
    }
    public  function type($type){
        $this->type = $type;
        return $this;
    }
    public  function queryOptions($queryOptions){
        $this->queryOptions = $queryOptions;
        return $this;
    }
    public function queryOption($queryOption){
        if(!$this->queryOptions){
            $this->queryOptions = [];
        }
        array_push($this->queryOptions,$queryOption);
    }
    public  function workflow($workflowId){
        $this->workflowId = $workflowId;
        return $this;
    }

    public function search(){
        $q = strtolower($this->q);
        $es = Elasticsearch::Instance();
        $page = $this->page ? $this->page : 1;
        $per_page = $this->perPage ? $this->perPage : 10;
        $per_page = $per_page <= 50 ? $per_page : 50;
        $options = [
            "networkId" => $this->networkId,
            "size" => $per_page,
            "from" => ($page - 1) * $per_page,
        ];
        if($this->workflowId) {
            $options['workflow_id'] = $this->workflowId;
        }
        if($this->sort) {
            $order = $this->order ? $this->order : 'asc';
            $options['sort'] = [$this->sort => $order];
        }
    
        if($this->type) {
            if(!$this->queryOptions){
                $this->queryOptions = [];
            }
            array_push($this->queryOptions,new QueryOption('match','oda_type',$this->type));
        }
        try{
            $search = $es->search($q,NetworkObject::class,$this->queryOptions,$options);
            $search->pages = countTotalPages($per_page,$search->hits->total);
            $search->current_page = $page;
            return $search;
        }catch(\Exception $e){
            return [];
        }        
    }

    
}