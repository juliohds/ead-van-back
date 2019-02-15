<?php

use App\User;

class WorkflowControllerTest extends TestCase
{
    

    public function testShowAllWorkflows(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/workflows',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertTrue($json[1]->enabled);

        $r = $this->json('GET','/api/2/workflows',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertFalse($json[1]->enabled);
    }

    public function testShowByPermission(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/workflows/auth',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(6,count($json));
        $this->assertEquals('new',$json[0]->tag);


        $token = AuthControllerTest::getToken($this,2);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/workflows/auth',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(5,count($json));
        
        $r = $this->json('GET','/api/2/workflows/auth',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(0,count($json));

    }

}

