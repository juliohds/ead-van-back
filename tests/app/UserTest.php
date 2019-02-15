<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
class UserTest extends TestCase
{
    public function testNetworkUsers()
    {
        $user = User::find(1);

        $this->assertEquals('Sebastião',$user->person->first_name);
        $this->assertEquals(3, count($user->person->academic->interests));
        $this->assertEquals(1, count($user->person->academic->grades));
        $this->assertEquals(1, $user->person->academic->school_id);
        $this->assertEquals(1, $user->person->academic->city_id);
        $this->assertEquals(2, $user->person->city_id);

    }
    public function testNewRefreshToken(){
        $user = User::find(1);

        $r1 = $user->newRefreshToken();
        $this->assertEquals(120,strlen($r1));

        $r2 = $user->newRefreshToken();
        $this->assertEquals(120,strlen($r2));

        $this->assertNotEquals($r1,$r2);
    }
}
