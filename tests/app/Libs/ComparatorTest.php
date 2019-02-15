<?php

use App\Libs\Comparator;
use App\MainObject;

class ComparatorTest extends TestCase
{
    public function testComparator()
    {
        $main1 = MainObject::find(1);
        $main1_1 = MainObject::find(1);
        $main2 = MainObject::find(2);
        $this->assertFalse(Comparator::compare($main1,$main2));
        $this->assertTrue(Comparator::compare($main1,$main1_1));
        $main1->oda->url = "novaurl";
        $this->assertFalse(Comparator::compare($main1,$main1_1));
    }    

    public function testComparatorAppendedAttribute()
    {
        $main1 = MainObject::find(1);
        $main1_1 = MainObject::find(1);
        $main1_1->setFacetOptionIds([4,5,6]);
        $this->assertFalse(Comparator::compare($main1,$main1_1));

        $main1_1->setFacetOptionIds([1,2]);
        $this->assertTrue(Comparator::compare($main1,$main1_1));
        $main1_1->setFacetOptionIds([2,1]);
        $this->assertTrue(Comparator::compare($main1,$main1_1));
    }    
}
