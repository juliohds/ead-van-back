<?php

use App\User;

class UserControllerTest extends TestCase
{
    

    public function testMe(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/users/me',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Sebastião',$json->person->first_name);
        $this->assertEquals('admin',$json->role);
    }

    public function testMeAdminWithoutRelation(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/4/users/me',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Sebastião',$json->person->first_name);
        $this->assertEquals('admin',$json->role);
    }

    public function testMeOtherWithoutRelation(){
        $token = AuthControllerTest::getToken($this,2);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/4/users/me',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Niva',$json->person->first_name);
        $this->assertEquals('user',$json->role);
    }

    public function testShowAll(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['Authorization' => $token];

        $r = $this->json('GET', '/api/1/users', [], $headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(200, $r->response->getStatusCode());
        
        $this->assertEquals(1,$json[0]->id);
        $this->assertEquals('Sebastião',$json[0]->person->first_name);
        $this->assertEquals('manager',$json[0]->person->profile->tag);

    }

    public function testShowAllNetworks(){
        
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ["Authorization" => $token];

        $r = $this->json("GET", "api/users",[],$headers);
        $json = (json_encode($r->response->getContent()));

        $this->assertEquals(200, $r->response->getStatusCode());
        $this->assertEquals(1,$json[0]->id);
        $this->assertEquals('Sebastião',$json[0]->person->first_name);
        $this->assertEquals('manager',$json[0]->person->profile->tag);
    }

}

