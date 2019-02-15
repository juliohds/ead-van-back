<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\NetworkObject;
class NetworkObjectTest extends TestCase
{
    public function testScopeByNetwork()
    {
        $no = NetworkObject::byNetwork(1,1);
        $this->assertNotNull($no);
    }

    public function testAllNetwork()
    {
        $nos = NetworkObject::allNetwork(1);
        $this->assertEquals(9,count($nos));
        $this->assertEquals(1,count($nos[0]->workflow_id));
    }

    public function testNetworksUsingThisObject(){
        $no = NetworkObject::find(1);
        $this->assertEquals(2,count($no->networksUsingThisObject()));
    }

}
