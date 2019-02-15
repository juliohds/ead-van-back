<?php
use App\User;
use App\MainObject;
use App\ClassPlan;
use App\NetworkObject;
use App\Workflow;

class CollaborateControllerTest extends TestCase
{
    public function testSaveOdaCollaborate(){
        
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['Authorization' => $token];
        
        $obj['main_object'] = [
            'oda_type' => 'oda',
            'title' => 'New teste save Oda',
            'description' => 'Taiga is a project management platform for agile
                developers & designers and project managers who want a beautiful 
                tool that makes work truly enjoyable.',
            'picture' => 'https://taiga.io/v-43b64963b3/images/logo.svg',
            'tags' => 'Taiga',
        ];

        $obj['oda'] = [
            'url' => 'https://taiga.io',
        ];

        $obj['facet_option_ids'] = [1,2];

        $r = $this->json('POST',  '/api/1/collaborate/oda', $obj,$headers);

        $this->assertEquals(201,$r->response->getStatusCode());
        $json = json_decode($r->response->getContent());
        $id = $json->id;

        $no = NetworkObject::where('main_object_id',$id)->first();
        $no->delete();

                    
        }

}

    