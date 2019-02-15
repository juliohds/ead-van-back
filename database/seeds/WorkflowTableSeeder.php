<?php

use Illuminate\Database\Seeder;
use App\Workflow;
class WorkflowTableSeeder extends Seeder
{
    public function run()
    {

        $values = [Workflow::NEW,Workflow::REVISION,Workflow::PUBLISHED,
            Workflow::ARCHIVED,Workflow::BROKEN,Workflow::DRAFT,Workflow::SUGGESTED];
        foreach($values as $value){
            $e = new Workflow;
            $e->id = $value;
            $e->tag = Workflow::defaultValue($value);
            $e->title =  translate(Workflow::defaultValue($value));
            $e->save();
        }
    }
}
