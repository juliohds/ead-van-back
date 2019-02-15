<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Network;
class NetworkTest extends TestCase
{
    public function testNetworkUsers()
    {
        $network = Network::find(1);

        $user = $network->networkUsers[0];
        $this->assertEquals('Sebastião',$user->user->person->first_name);
    }

    public function testConfig(){
        $network = Network::find(1);
        $homeFacets = $network->networkConfig->homeFacets;
        $this->assertEquals(3,count($homeFacets));
    }
}
