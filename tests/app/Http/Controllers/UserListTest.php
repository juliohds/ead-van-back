<?php

use App\User;

class UserListTest extends TestCase
{

    public function testShowListAndTotalFavority()
    {

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();

        $r = $this->json('GET', '/api/1/user-list-total',[], ['Authorization' => $token]);
        $this->assertEquals(200,$r->response->getStatusCode());

    }

    public function testShowAll(){
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();

        $r = $this->json('GET', '/api/1/user-list',[], ['Authorization' => $token]);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(200, $r->response->getStatusCode());

        $this->assertEquals(2, $json[0]->items_count);

    }
}

