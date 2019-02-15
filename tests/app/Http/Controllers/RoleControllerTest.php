<?php

use App\User;

class RoleControllerTest extends TestCase
{

    public function testUpdate(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['Authorization'=>$token];

        $obj['role_id'] = 1;

        $r = $this->json('PUT', 'api/1/role/1', $obj, $headers);
        $json = (json_decode($r->response->getContent()));

        $this->assertEquals(200, $r->response->getStatusCode());
        $this->assertEquals(1, $json->id);
        $this->assertEquals(1, $json->network_id);
        $this->assertEquals($obj['role_id'], $json->role_id);

        }
}

