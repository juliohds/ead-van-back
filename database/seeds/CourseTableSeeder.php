<?php

use Illuminate\Database\Seeder;
use App\Course;
use App\MainObject;
use App\TargetObject;
class CourseTableSeeder extends Seeder
{
    public function run()
    
    {
        $target = $this->newTarget();
        
        for($i = 0;$i < 10; $i++){
            if($i % 3 == 0){
                $target = $this->newTarget();
            }
            $this->newCourse($target,$i);
        }
        
    }

    private function newTarget(){
        $target = new TargetObject;
        $target->user_id = 1;
        $target->network_id = 1;
        $target->save();
        return $target;
    }

    private function newCourse($target,$index){
        $variant = $index.'';
        $main = new MainObject;
        $main->picture = 'https://images.pexels.com/photos/256468/pexels-photo-256468.jpeg';
        $main->tags = "any course$variant,course$variant";
        $main->title = "Course$variant";
        $main->description = "Www's giant.";


        $course = new Course;
        $course->url= "http://google.com.br?q=Courses online";
        $course->total_hours = 40;
        $course->save();
        $main->oda()->associate($course);
        $main->target()->associate($target);

        $main->save();
        $main->facetOptions()->sync([$index+1,2]);
        return $main;        
    }
}
