<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\ClassPlan;
use App\ClassPlanUrl;
class ClassPlanTest extends TestCase
{
    public function testUrls()
    {
        $cp = ClassPlan::find(1);

        $urls = $cp->allUrls();
        $this->assertEquals('http://outraurl.com',$urls[0]);
        $this->assertEquals(1,count($urls));
    }

    public function testCreateClassPlan(){
        $cp = new ClassPlan;
        $arr = [
            'oda_ids' => [1,2],
            'plan_process' => 'Plan Proccess',
            'goal' => 'Goal',
            'duration' => '3 hours',
            'required_supplies' => 'Required supplies',
            'evaluation' => 'Evaluation',
            'urls' => ['http://google.com.br','https://andersonlira.com']
        ];

        $totalUrls = count(ClassPlanUrl::all());


        $cp->fill($arr);
        $cp->save();

        $this->assertTrue($cp->id > 0);
        $this->assertEquals('Plan Proccess',$cp->plan_process);
        $this->assertEquals(2,count($cp->classPlanUrls()->get()));

        $cp->delete();
        //Asserting urls is garbaged
        $this->assertEquals($totalUrls,count(ClassPlanUrl::all()));

    }
}
