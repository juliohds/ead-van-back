<?php

namespace App\Http\Controllers;

use App\MainObject;
use App\MainObjectSecundaryCurator;
use App\TargetObject;
use App\ObjectSlug;
use App\Oda;
use App\ClassPlan;
use App\DevelopmentResource;
use App\User;
use App\Person;
use App\FacetOption;
use App\Grade;
use App\Interest;
use App\Course;
use App\NetworkObject;
use App\CurateAux;
use App\Workflow;
use App\Libs\Comparator;
use App\Libs\SearchingBuilder;
use App\Libs\QueryOption;
use App\Jobs\ImportJob;

use App\Helpers\FacetHelper;
use Illuminate\Http\Request;

class MainObjectController extends CuratorialController
{
    public function protectedMethods(){
        return ['create','update','delete'];
    } 

    public function showAll(){
        
            return $this->responseOK($this->basicbuilder()->search());
        
    }

    public function suggestedHome($idNetwork){
        
        if($this->input('area') == null && $this->input('serie') == null){
            
            $no = NetworkObject::where('network_id', $idNetwork)->with('mainObject')
                                ->join('main_object', 'network_object.main_object_id', '=', 'main_object.id')
                                ->where('main_object.oda_type', 'oda')
                                ->select('network_object.*', 'main_object.*')
                                ->limit(8)->inRandomOrder()
                                ->get();
        }

        else if($this->input('area') != null && $this->input('serie') != null) {
        
            $facet_id_area = $this->facetOptionIdByinterestId($this->input('area'));
            $facet_id_serie = $this->facetOptionIdByGradeId($this->input('serie'));
            $facet_ids = array($facet_id_area, $facet_id_serie);
            
            $no = NetworkObject::where('network_id', $idNetwork)->with('mainObject')
                                ->join('main_object', 'network_object.main_object_id', '=', 'main_object.id')
                                ->where('main_object.oda_type', 'oda')
                                ->join('object_facet_option', 'network_object.main_object_id', '=', 'object_facet_option.main_object_id')
                                ->whereIn('object_facet_option.facet_option_id', $facet_ids)
                                ->limit(8)->inRandomOrder()
                                //->select('network_object.*', 'main_object.*')
                                ->get();

        }

        else if($this->input('area') != null || $this->input('serie') != null) {
            
            if($this->input('area') != null){
                $facet_id = $this->facetOptionIdByinterestId($this->input('area'));
            }else{
                $facet_id = $this->facetOptionIdByGradeId($this->input('serie'));
            }
            
            $no = NetworkObject::where('network_id', $idNetwork)->with('mainObject')
                                ->join('main_object', 'network_object.main_object_id', '=', 'main_object.id')
                                ->where('main_object.oda_type', 'oda')
                                ->join('object_facet_option', 'network_object.main_object_id', '=', 'object_facet_option.main_object_id')
                                ->where('object_facet_option.facet_option_id', $facet_id)
                                ->limit(8)->inRandomOrder()
                                //->select('main_object.*')
                                ->get();

        }

        return response()->json($no);

    }

    public function facetOptionIdByGradeId($grade){
        $facet_id = FacetOption::where('title', 'like', '%'.$grade.'%')->pluck('id')->first();
        return $facet_id;
    }

    public function facetOptionIdByinterestId($interest){
        $facet_id = FacetOption::where('title', 'like', '%'.$interest.'%')->pluck('id')->first();
        return $facet_id;
    }

    public function showAllOthers(){
        $builder = $this->basicBuilder();
        $builder->network("-".$this->currentNetworkID());
        $builder->queryOption(
            new QueryOption(
                'match',
                'other_network_ids',
                $this->currentNetworkID(),
                true
            )
        );
        return $this->responseOK($builder->search());

    }
    private function basicBuilder(){
        $builder = SearchingBuilder::builder()
            ->network($this->currentNetworkID())
            ->q($this->input('q'))
            ->page($this->input('page'))
            ->perPage($this->input('per_page'))
            ->sort($this->input('sort'))
            ->order($this->input('order'))
            ->type($this->input('type'))
            ->workflow($this->input('workflow_id'));
        if($this->input('title')){
            $builder->queryOption(new QueryOption(
                'wildcard',
                'title',
                strtolower($this->input('title'))
            ));
        }
        return $builder;
            //->queryOptions($this->getQueryOptions());

    }
    private function getQueryOptions(){
        $queryOptions = [];
        $ni = $this->currentNetworkID();
        $qo = new QueryOption(
            'match',
            'facet_option_ids',
            $id
        );
        array_push($queryOptions,$qo);
        return $queryOptions;
    }

    public function showById($id)
    {
        if (!is_numeric($id)){
            $slug = ObjectSlug::where('slug',$id)->First();
            if($slug){
                $id = $slug->main_object_id;
            }else{
                $id = -1;
            }
        }
        $no = NetworkObject::byNetwork($id,$this->currentNetworkID())->first();
        if(!$no){
            throw new \App\Exceptions\NotFoundException;
        }
        return $this->responseOK($no->toView());

    }

    public function showByOldId($id)
    {
       
        $no = TargetObject::where("old_id","=",$id)->first();
        $mo = MainObject::where("target_object_id",$no->id)->first();
        if(!$no){
            throw new \App\Exceptions\NotFoundException;
        }
        return $this->responseOK($mo);

    }

    public function create(){
        $this->validation(MainObject::class);
        try {

            $target = new TargetObject;
            $target->user_id = $this->currentUserID();
            $target->network_id = $this->currentNetworkID();
            $target->save();

            $main = new MainObject;
            $main->fill($this->input());

            $oda = $this->createNewOda();
            $oda->save();

            $main->oda()->associate($oda);
            $main->target()->associate($target);

            $main->setFacetOptionIds($this->input('facet_option_ids'));

            $main->save();


            $no = new NetworkObject([
                'network_id' => $this->currentNetworkID(),
                'main_object_id' => $main->id,
                'workflow_id' => $this->input('workflow_id')
            ]);
            $no->save();

            return $this->responseCreated($no->toView());

        }catch(Exception $e){
            throw new \App\Exceptions\BadRequestException;
        }


    }

    public function update($id){
        
        $no = NetworkObject::byNetwork($id,$this->currentNetworkID())->first();
        if (!$no) {
            throw new \App\Exceptions\NotFoundException;
        }
     
        $mainObject = $this->sameOrNewVersion($no->mainObject);
        $no->main_object_id = $mainObject->id;
        $no->workflow_id = $this->input('workflow_id');

        if($mainObject->curator_main_id != null)
        {   
            $user_id = $this->currentUserID();

            $curate_aux = CurateAux::where('main_object_id', $mainObject->id)
                                    ->where('user_id', $user_id)->first();

            if($curate_aux == null){
                $ca = new CurateAux;
                $ca->main_object_id = $mainObject->id;
                $ca->user_id = $user_id;
                $ca->save();
            }
            
        }else{
            $mainObject->curator_main_id = $this->currentUserID();
        }

        $mainObject->update();
        $no->update();

        $this->deleteOrphan($id);
        //Force Reload
        $no = NetworkObject::find($no->id);
        return $this->responseOK($no->toView());
    }

    public function delete($id){
        $no = NetworkObject::byNetwork($id,$this->currentNetworkID())->first();
        if(!$no){
            throw new \App\Exceptions\NotFoundException;
        }
        $no->archieve();
    }



    public function versions($id){
       $main = MainObject::find($id);
       if(!$main){
           throw new App\Exceptions\NotFoundException;
       }
       $ids = $main->target->versions->pluck('id');
       return $this->responseOK($ids);
    }

    public function import(){
        $this->validate($this->request,[
            'main_object_ids.0' => 'required'
        ]);


        $job = (new ImportJob($this->input('main_object_ids.0'),$this->currentNetworkID()));
        dispatch($job);
        return $this->responseOK([]);

    }

    public function userInfo(){
        $this->validate($this->request,[
            'main_object_ids.0' => 'required'
        ]);
        $main_object_ids = $this->input('main_object_ids');
        $result = [];
        foreach(MainObject::whereIn('id',$main_object_ids)->get() as $main){
            $obj = [
                'main_object_id' => $main->id,
                'user_info' => $main->target->getUserInfoAttribute()
            ];
            array_push($result,$obj);
        }
        return $this->responseOK($result);
    }

    private function prepareView($obj,$networkObject){
        $obj->workflow_id =$networkObject->workflow->id;
        $obj->oda;
        $fh = new FacetHelper;
        $fh->mergedFacets($obj,$this->request->currentNetwork->id);

    }

    private function createNewOda(){
        $odaTypeMethod = $this->input('oda_type').'Create';
        $oda = $this->$odaTypeMethod();
        $oda->fill($this->input('oda'));
        return $oda;
    }
    private function development_resourceCreate(){
        $oda = new DevelopmentResource;
        return $oda;
    }
    private function courseCreate(){
        $oda = new Course;
        return $oda;
    }
    private function odaCreate(){
        $oda = new Oda;
        return $oda;
    }
    private function class_planCreate(){
        $oda = new ClassPlan;
        return $oda;
    }

    private function sameOrNewVersion($main){
        $main->fill($this->input());
        $main->oda->fill($this->input('oda'));
        $main->setFacetOptionIds($this->input('facet_option_ids'));
        $versions = $main->target->versions;

        $same = null;
        foreach($versions as $version){
            if(Comparator::compare($main,$version)){
               $same = $version;
               break;
            }else{
                //Check if just current network assinged object
                if($main->id == $version->id){
                    $count  = NetworkObject::where('main_object_id',$main->id)->count();

                    if($count == 1){
                        $same = $main;
                        break;
                    }
                }
            }
        }
        if(!$same){
            $target = $main->target;
            $main = new MainObject;
            $oda = $this->createNewOda();
            $oda->fill($this->input('oda'));
            $oda->save();
            $main->oda()->associate($oda);
            $main->target()->associate($target);
            $main->fill($this->input());
            $main->setFacetOptionIds($this->input('facet_option_ids'));
        }else{
            $main = $same;
        }
        $main->save();
        $main->oda->save();
        return $main;
    }

    private function deleteOrphan($id){
        $no = NetworkObject::where('main_object_id',$id)->first();
        if(!$no){
            $main = MainObject::find($id);
            if($main){
                foreach($main->facetOptions as $option){
                    $main->facetOptions()->detach($option->id);
                }
                $main->delete();
            }
        }
    }

}
