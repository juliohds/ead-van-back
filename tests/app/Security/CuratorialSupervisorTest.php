<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Security\CuratorialSupervisor;
use App\Role;
use App\Workflow;
use App\Network;

class CuratorialSupervisorTest extends TestCase
{
    /**
    * @expectedException App\Exceptions\BadRequestException
    */
    public function testConstructorException()
    {
        $cs = new CuratorialSupervisor(1,1,1,null);
    }

    /**
    * @expectedException App\Exceptions\DeniedException
    */
    public function testCanDoDenied ()
    {
        $network = Network::find(1);
        $cs = new CuratorialSupervisor(Workflow::NEW,Workflow::PUBLISHED,$network,Role::CURATOR);
        $cs->canDo();
    }

    public function testCanDoOk ()
    {
        $network = Network::find(4);
        $cs = new CuratorialSupervisor(Workflow::NEW,Workflow::PUBLISHED,$network,Role::CURATOR);
        $this->assertTrue($cs->canDo());
    }

    public function testCanSee()
    {
        $network = Network::find(1);
        $cs = new CuratorialSupervisor(Workflow::NEW,Workflow::PUBLISHED,$network,Role::CURATOR);
        $this->assertTrue($cs->canSee());

        $network = Network::find(2);
        $cs = new CuratorialSupervisor(Workflow::NEW,Workflow::PUBLISHED,$network,Role::CURATOR);
        $this->assertTrue($cs->canSee());

    }

    


}
