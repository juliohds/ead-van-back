<?php

use Illuminate\Database\Seeder;
use App\ClassPlan;
use App\ClassPlanUrl;
use App\MainObject;
use App\TargetObject;

class ClassPlanTableSeeder extends Seeder
{
    public function run()
    
    {
        $target = $this->newTarget();
        
        for($i = 0;$i < 10; $i++){
            if($i % 3 == 0){
                $target = $this->newTarget();
            }
            $this->newClassPlan($target,$i);
        }
        
    }

    private function newTarget(){
        $target = new TargetObject;
        $target->user_id = 1;
        $target->network_id = 1;
        $target->save();
        return $target;
    }

    private function newClassPlan($target,$index){
        $variant = $index.'';
        $main = new MainObject;
        $main->picture = 'https://www.google.com.br/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png';
        $main->tags = "search site$variant,web$variant";
        $main->title = "Google$variant";
        $main->description = "Www's giant.";


        $cp = new ClassPlan;
        $cp->plan_process = '<strong>Plan Process</strong><br>';
        $cp->goal = '<strong>Goal</strong><br>';
        $cp->duration = '2 horas';
        $cp->required_supplies = '<strong>Required supplies</strong><br>';
        $cp->evaluation = '<strong>Plan Process</strong><br>';
        $cp->save();
        $cp->odas()->sync([1,2]);
        
        $main->oda()->associate($cp);
        $main->target()->associate($target);

        $main->save();
        $main->facetOptions()->sync([$index+1,2]);

        $cpu = new ClassPlanUrl;
        $cpu->class_plan_id = $cp->id;
        $cpu->url = "http://outraurl.com";
        $cpu->save();
        
        return $main;        
    }
}
