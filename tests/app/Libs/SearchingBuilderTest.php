<?php

use App\Libs\SearchingBuilder;
use App\Workflow;

class SearchingBuilderTest extends TestCase
{
    public function testBuilder()
    {
        TestCase::index();
        $builder = SearchingBuilder::builder();
        $builder->network(1)
            ->q('Goo')
            ->page(1)
            ->perPage(50)
            ->workflow(Workflow::PUBLISHED);
        $result = $builder->search();
        $this->assertEquals(6, $result->hits->total);

    }   


}
