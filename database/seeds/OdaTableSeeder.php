<?php

use Illuminate\Database\Seeder;
use App\Oda;
use App\MainObject;
use App\TargetObject;
class OdaTableSeeder extends Seeder
{
    public function run()
    
    {
        $target = $this->newTarget();
        
        for($i = 0;$i < 10; $i++){
            if($i % 3 == 0){
                $target = $this->newTarget();
            }
            $this->newOda($target,$i);
        }
        
    }

    private function newTarget(){
        $target = new TargetObject;
        $target->user_id = 1;
        $target->network_id = 1;
        $target->save();
        return $target;
    }

    private function newOda($target,$index){
        $variant = $index.'';
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
        $main->facetOptions()->sync([$index+1,2]);
        return $main;        
    }
}
