<?php

use Illuminate\Database\Seeder;
use App\Evaluation;

class EvaluationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $evaluates = [[1,2,3],[4,5]];

        foreach($evaluates as $evaluate){
            foreach($evaluate as $item){
                $e = new Evaluation;
                $e->pedagogical = $item;
                $e->content = $item;
                $e->technical = $item;
                $e->network_user_id = 1;
                $e->target_object_id = $item;
                $e->save();
            }
    

        }

        $e = new Evaluation;
        $e->pedagogical = 3;
        $e->content = 3;
        $e->technical = 3;
        $e->network_user_id = 1;
        $e->target_object_id = 1;
        $e->save();
    }
}
