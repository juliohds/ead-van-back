<?php

use App\User;

class EvaluationControllerTest extends TestCase
{

    public function testShowEvaluationTargetObject(){

        $user = new User;
        $token = "Bearer ".$user->newToken();

        $r = $this->get('/api/1/target-object/1/evaluation', ['HTTP_Authorization' => $token]);
        $json = (json_decode($r->response->getContent()));

        $this->assertContains($json[0]->technical, [1,2,3]);
        $this->assertContains($json[0]->pedagogical, [1,2,3]);
        $this->assertContains($json[0]->content, [1,2,3]);

    }


    public function testShowAll(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();

        $headers = ['Authorization' => $token];
        $r = $this->json('GET', '/api/1/evaluation',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(200,$r->response->getStatusCode());

        $this->assertEquals(2,$json[0]->technical);
        $this->assertEquals(2,$json[0]->pedagogical);
        $this->assertEquals(2,$json[0]->content);

        $this->assertEquals('Sebastião',$json[0]->network_user->user->person->first_name);
    }

}