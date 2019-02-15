<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class MathHelperTest extends TestCase
{
    
    public function testCountTotalPages(){
        $perPage = 3;
        $total = 8;
        $pages = countTotalPages($perPage,$total);
        $this->assertEquals(3,$pages);

        $perPage = 3;
        $total = 9;
        $pages = countTotalPages($perPage,$total);
        $this->assertEquals(3,$pages);

        $perPage = 3;
        $total = 10;
        $pages = countTotalPages($perPage,$total);
        $this->assertEquals(4,$pages);


        $perPage = 3;
        $total = 11;
        $pages = countTotalPages($perPage,$total);
        $this->assertEquals(4,$pages);

    }
}
 