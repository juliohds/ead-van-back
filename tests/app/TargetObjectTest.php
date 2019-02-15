<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\TargetObject;
class TargetObjectTest extends TestCase
{
    public function testEvaluationAverage()
    {
        $obj = TargetObject::find(1);
        $ea =  $obj->evaluationAverage();
        $this->assertEquals(2,$obj->evaluationAverage());
    }
}
