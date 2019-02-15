<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\NetworkFacet;
class NetworkFacetTest extends TestCase
{
    public function testOptionRelation()
    {
        $nfo = NetworkFacet::find(1);
        $this->assertEquals(3,count($nfo->options));
    }
}
