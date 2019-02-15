<?php

use App\User;

class FavorityControllerTest extends TestCase
{

    public function testShowAllFavorityTargetObject()
    {
        $user = new User;
        $token = "Bearer ".$user->newToken();
        $r = $this->get('/api/1/my-favoritys-target-object', ['HTTP_Authorization' => $token]);
        $this->assertResponseStatus(500);
    }
  
}