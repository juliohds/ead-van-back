<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\MainObject;
class MainObjectTest extends TestCase
{
    public function testIndexable()
    {
        $main = MainObject::find(1);

        $indexable = $main->getIndexable();
        $this->assertRegexp('"Google0"', $indexable);
        $this->assertRegexp('[1]', $indexable);
    }

    public function testFacetOptionIdsAttribute(){
        $main = MainObject::find(1);
        $this->assertTrue([1,2] == $main->facet_option_ids);        
    }

    public function testSetFacetOptionIds(){
        $main = new MainObject;
        $main->setFacetOptionIds([1,2]);
        $this->assertTrue([1,2] == $main->facet_option_ids);        
    }

}
