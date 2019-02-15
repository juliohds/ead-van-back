<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\FacetType;
class FacetTypeTest extends TestCase
{
    public function testValueOf()
    {
        $this->assertEquals(FacetType::ODA,FacetType::valueOf('oda'));
        $this->assertEquals(FacetType::COURSE,FacetType::valueOf('course'));
    }

    /**
    * @expectedException App\Exceptions\InvalidArgumentException
    */
    public function testValueOfException(){
        $this->assertEquals(0,FacetType::valueOf('undefined'));
    }
}
