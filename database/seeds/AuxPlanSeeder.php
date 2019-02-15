<?php

use Illuminate\Database\Seeder;
use App\School;
use App\TypeSchool;
use App\City;
use App\Oda;
use App\Network;
use App\NetworkObject;
use App\NetworkFacetOption;
use App\MainObject;
use App\TargetObject;
use App\Workflow;

class AuxPlanSeeder extends Seeder
{
    public function run()
    {

        $file = fopen("database/import/ODAS_SUBIR.csv","r");
        
        $aux = 0;
        $nu_id = Network::where('name', 'Escola Digital')->pluck('id')->first();

        while(! feof($file))
        {
            $aux ++;
            $odaCsv = fgetcsv($file);

            $tg = TargetObject::where('old_id', trim($odaCsv[18]))->first();

            if($tg == null){ println('Não TG nesse old_id: '.$odaCsv[18].' titulo '.$odaCsv[0]); continue; }

            $mo_ids = MainObject::where('target_object_id', $tg->id)->pluck('id')->toArray();

            if(count($mo_ids)>1){
                $mo_id = NetworkObject::where('network_id', $nu_id)->whereIn('main_object_id', $mo_ids)->pluck('main_object_id')->first();
                $mo = MainObject::where('id', $mo_id)->with('oda')->first();
            }else{
                $mo = MainObject::whereIn('id', $mo_ids)->with('oda')->first();
            }

            $facet_option_ids = [];

            if($mo != null){

                $mo->title = trim($odaCsv[0]);

                $oda = Oda::find($mo->oda_id)->first();
                $oda->url = trim($odaCsv[1]);
                $oda->update();

                $mo->description = trim($odaCsv[2]);
                $mo->tags = trim(str_replace(';',',', trim($odaCsv[3])));
                $mo->bncc_tags = trim(str_replace("\"","", str_replace(')','', str_replace('(','', str_replace(')(',',', str_replace(' ','',str_replace('.','', str_replace(';',',', $odaCsv[4]))))))));
               
                $mo->produced_by = $odaCsv[15];
                
                if($odaCsv[4] != "" || $odaCsv[4] != null){
                    $mo->bncc_ok = true;
                }

                for ($i=5; $i < 18; $i++) {
                    if($odaCsv[$i] != null && $odaCsv[$i] != "" && $i != 15 && $i != 14){
                        if($i == 13 || $i == 17){
                            $exploded=preg_split('#(\s+)?[;/](\s+)?#', $odaCsv[$i]);
                            for ($j=0; $j < count($exploded); $j++) {
                                $id = NetworkFacetOption::whereRaw("unaccent(upper(title)) like unaccent(upper('".str_replace("'","",$exploded[$j])."'))")->pluck('facet_option_id')->first();
                                if($id != null){
                                    //println($exploded[$j]);
                                    if(!in_array($id,$facet_option_ids)){
                                        array_push($facet_option_ids, $id);
                                    }
                                }
                            }

                        }else{
                            $id = NetworkFacetOption::whereRaw("unaccent(upper(title)) like unaccent(upper('".str_replace("'","",$odaCsv[$i])."'))")->pluck('facet_option_id')->first();
                            if($id != null){
                                //println($odaCsv[$i]);
                                if(!in_array($id,$facet_option_ids)){
                                    array_push($facet_option_ids, $id);
                                }
                            }
                        }

                    }
                }
                //var_dump($facet_option_ids);
                $mo->setFacetOptionIds($facet_option_ids);
                $mo->update();

                $facet_option_ids = [];
                
            }else{
                println($aux.' nao contem MO old_id: '.$odaCsv[18].' titulo '.$odaCsv[0]);
            }

        }

        fclose($file);

    }
    private function newTarget(){
        $target = new TargetObject;
        $target->user_id = 1;
        $target->network_id = 1;
        $target->save();
        return $target;
    }

    private function newOda($target){
        $variant = '';
        $main = new MainObject;
        $main->picture = 'https://www.google.com.br/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
        $main->tags = "search site$variant,web$variant";
        $main->title = "Google$variant";
        $main->description = "Www's giant.";


        $oda = new Oda;
        $oda->url= "http://google.com.br?q=$variant";
        $oda->save();
        $main->oda()->associate($oda);
        $main->target()->associate($target);

        $main->save();
        //$main->facetOptions()->sync([$index+1,2]);
        return $main;
    }
}
