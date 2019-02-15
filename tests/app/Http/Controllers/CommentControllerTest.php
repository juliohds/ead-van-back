<?php

use App\User;

class CommentControllerTest extends TestCase
{

    public function testShowComments()
    {
        
        $r = $this->json('GET', '/api/1/target-object/1/comments', []);
        $json = (json_decode($r->response->getContent()))[0];

        $this->assertEquals(1, $json->id);
        $this->assertEquals('Tenetur sint voluptas quis dignissimos repudiandae neque architecto.', $json->text);
        $this->assertEquals(1, $json->network_user_id);
        $this->assertEquals(1, $json->target_object_id);

    }

    public function testUpdate(){
        
        $user = User::find(1);
        $token = "Bearer ". $user->newToken();
        $headers = ['Authorization'=>$token];
        $obj["text"] = "Testado pelo test unitário";
        
        $r = $this->json('PUT', 'api/1/comments/1', $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        
        $this->assertEquals(200, $r->response->getStatusCode());
        $this->assertEquals($obj["text"], $json->text);

    }
    public function testShowAll(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();

        $headers = ['Authorization' => $token];
        $r = $this->json('GET', '/api/1/comments',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(200,$r->response->getStatusCode());

        $this->assertEquals('Tenetur sint voluptas quis dignissimos repudiandae neque architecto.',$json[0]->text);
        $this->assertEquals('Sebastião',$json[0]->network_user->user->person->first_name);

    }
}