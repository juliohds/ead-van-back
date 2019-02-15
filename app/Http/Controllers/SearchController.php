<?php

namespace App\Http\Controllers;

use App\MainObject;
use App\Network;
use App\NetworkObject;
use App\Workflow;
use App\Libs\SearchingBuilder;
use App\Libs\QueryOption;
use Illuminate\Http\Request;

class SearchController extends Controller

{

    public function searchText()
    {
        return response()->json($this->searching());
    }

    public function homeFacets(){
        return response()->json($this->searching());
        
    }

    private function searching(){
        $builder = SearchingBuilder::builder()
            ->network($this->currentNetworkID())
            ->q($this->input('q'))
            ->page($this->input('page'))
            ->perPage($this->input('per_page'))
            ->sort($this->input('sort'))
            ->order($this->input('order'))
            ->type($this->input('type'))
            ->workflow(Workflow::PUBLISHED)
            ->queryOptions($this->getQueryOptions());
        return $builder->search();        
    }
    private function getQueryOptions(){
        $queryOptions = [];
        $op_ids = $this->request->input('option_ids');
        if($op_ids){
            foreach($op_ids as $id){
                $qo = new QueryOption(
                    'match',
                    'facet_option_ids',
                    $id
                );
                array_push($queryOptions,$qo);
            }
        }
        $bncc_tags = $this->request->input('bncc_tags');
        if($bncc_tags){
            foreach($bncc_tags as $tag){
                $qo = new QueryOption(
                    'wildcard',
                    'bncc_tags',
                    "*".strtolower($tag)."*",
                    false,
                    true

                );
                array_push($queryOptions,$qo);
            }
        }
        return $queryOptions;
    }

}