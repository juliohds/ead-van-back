<?php
use App\User;
use App\MainObject;
use App\MainObjectSecundaryCurator;
use App\ClassPlan;
use App\NetworkObject;
use App\Workflow;
class MainObjectControllerTest extends TestCase
{

    public function testShowAll(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/main-object?per_page=20',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(10,$json->hits->total);
    }
    public function testShowAllOthers(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/main-object-others',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2,$json->hits->total);
        $this->assertTrue(
            9 == $json->hits->hits[0]->_source->id || 18 == $json->hits->hits[0]->_source->id
        );

        $r = $this->json('GET','/api/1/main-object-others?title=Google8',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(1,$json->hits->total);

    }

    public function testShowAllPaginate(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/main-object?page=1&per_page=1',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(1,count($json->hits->hits));

        $id1 = $json->hits->hits[0]->_source->id;

        $r = $this->json('GET','/api/1/main-object?page=2&per_page=1',[],$headers);
        $json = (json_decode($r->response->getContent()));

        $id2 = $json->hits->hits[0]->_source->id;
        $this->assertNotEquals($id1,$id2);
    }

    public function testShowByTitle(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];

        $r = $this->json('GET','/api/1/main-object?title=Google2',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2,$json->hits->total);
    }

    public function testMainObjects()
    {
        $this->json('GET', '/api/1/main-object/1', [])
             ->seeJson([
                'id' => 1,
                'title' => 'Google0',
                'workflow_id' => Workflow::PUBLISHED,
                'url' => 'http://google.com.br?q=0',
                'facet_option_ids' => [2,1],
                'full_name' => 'Sebastião '
             ]);
        $this->json('GET', '/api/1/main-object/google0-1', [])
             ->seeJson([
                'id' => 1,
                'title' => 'Google0',
                'workflow_id' => Workflow::PUBLISHED,
                'url' => 'http://google.com.br?q=0',
                'facet_option_ids' => [2,1],
                'full_name' => 'Sebastião '
             ]);

             $this->json('GET', '/api/1/main-object/9999999', [])
             ->seeJson([
                'message' => 'Not Found'
            ]);

    }

    public function testCreateOda(){
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();

        $obj = [
            'oda_type' => 'oda',
            'title' => 'New Oda created',
            'description' => 'GitHub is a development platform inspired
                by the way you work. From open source to business, you
                can host and review code, manage projects, and build
                software alongside millions of other developers',
            'picture' => 'https://avatars3.githubusercontent.com/u/7696787?s=460&v=4',
            'tags' => 'Git hub, internet tool',
            'workflow_id' => Workflow::PUBLISHED,
            'bncc_tags' => 'EF010101,EF020202',
            'bncc_ok' => true,
            'facet_option_ids' => [1,2],

        ];
        $headers = ['Authorization' => $token];
        $r = $this->json('POST',  '/api/1/main-object', $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(422,$r->response->getStatusCode());

        $obj['oda'] = [
            'url' => 'http://github.com'
        ];

        $r = $this->json('POST',  '/api/1/main-object', $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(201,$r->response->getStatusCode());
        $this->assertEquals('New Oda created',$json->title);
        $this->assertEquals(3,$json->workflow_id);
        $this->assertEquals('http://github.com',$json->oda->url);
        $this->assertEquals('EF010101,EF020202',$json->bncc_tags);
        $this->assertEquals(true,$json->bncc_ok);
        $this->assertEquals(2,$json->facets[0]->options[0]->id);


    }

    public function testCreateClassPlanController(){
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['Authorization' => $token];

        $obj = [
            'oda_type' => 'class_plan',
            'title' => 'New ClassPlan created',
            'description' => 'Taiga is a project management platform for agile
                developers & designers and project managers who want a beautiful
                tool that makes work truly enjoyable.',
            'picture' => 'https://taiga.io/v-43b64963b3/images/logo.svg',
            'tags' => 'Taiga',
            'workflow_id' => Workflow::PUBLISHED,
            'facet_option_ids' => [1,2],

        ];

        $obj['oda'] = [
            'oda_ids' => [1,2],
            'plan_process' => 'Plan Proccess Taiga',
            'goal' => 'Goal Taiga',
            'duration' => '3 hours',
            'required_supplies' => 'Required supplies Taiga',
            'evaluation' => 'Evaluation Taiga',
            'urls' => ['https://taiga.io','https://andersonlira.com']
        ];
        $r = $this->json('POST',  '/api/1/main-object', $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(201,$r->response->getStatusCode());
        $this->assertEquals('New ClassPlan created',$json->title);
        $this->assertEquals(Workflow::PUBLISHED,$json->workflow_id);
        $this->assertEquals('Plan Proccess Taiga',$json->oda->plan_process);
        $this->assertEquals(2,$json->facets[0]->options[0]->id);
        $this->assertEquals('Google0',$json->oda->odas[0]->title);


    }



    public function testDelete(){
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];

        $ids = MainObject::where('title','New Oda created')
            ->orWhere('title','New ClassPlan created')->pluck('id')->toArray();
        foreach($ids as $id){
            $r = $this->json('DELETE',  "/api/1/main-object/$id",[],$headers);
            $this->assertEquals(200,$r->response->getStatusCode());
        }
    }

    public function testVersionIds(){
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];
        $r = $this->json('GET',  '/api/1/main-object/1/versions', [],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals([1,2,3],$json);
    }
    public function testUpdateOtherVersion(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];
        //Object 1 is shared with 2 networks
        $r = $this->json('GET',  '/api/1/main-object/1', [],$headers);
        $json = (json_decode($r->response->getContent()));

        $originURL = $json->oda->url;
        $originID = $json->id;

        $json->oda->url = 'http://andersonlira.com';
        $json->workflow_id = Workflow::NEW;

        $arr = (array) $json;
        $r = $this->json('PUT',  '/api/1/main-object/1',$arr,$headers);
        $json = (json_decode($r->response->getContent()));
        $newID = $json->id;
        $this->assertNotEquals($originID,$newID);

        $json->oda->url = $originURL;
        $json->workflow_id = Workflow::PUBLISHED;
        $arr = (array) $json;
        $r = $this->json('PUT',  "/api/1/main-object/$newID",$arr,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals($originID,$json->id);

    }
    public function testUpdateClassPlan(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];
        //Object 11 is a ClassPlan
        $r = $this->json('GET',  '/api/1/main-object/11', [],$headers);
        $json = (json_decode($r->response->getContent()));

        $originalId = $json->id;
        $originGoal = $json->oda->goal;


        $json->oda->goal = 'New Goal Updated';
        $json->workflow_id = Workflow::PUBLISHED;

        $arr = (array) $json;
        $r = $this->json('PUT',  '/api/1/main-object/11',$arr,$headers);

        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('New Goal Updated',$json->oda->goal);
        $this->assertEquals($originalId,$json->id);

        $json->oda->goal = $originGoal;
        $json->workflow_id = Workflow::PUBLISHED;
        $arr = (array) $json;
        $r = $this->json('PUT',  "/api/1/main-object/11",$arr,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals($originGoal,$json->oda->goal);

    }

    public function testUpdateOtherVersionWithFacets(){

        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];
        //Object 1 is shared with 2 networks
        $r = $this->json('GET',  '/api/1/main-object/1', [],$headers);
        $json = (json_decode($r->response->getContent()));

        $originID = $json->id;

        $json->workflow_id = Workflow::NEW;
        $originFacetsIds = [];

        foreach($json->facets as $facet){
            foreach($facet->options as $option){
                array_push($originFacetsIds,$option->facet_option_id);
            }
        }
        $json->facet_option_ids = [3,4];
        $arr = (array) $json;

        $r = $this->json('PUT',  '/api/1/main-object/1',$arr,$headers);
        $json = (json_decode($r->response->getContent()));
        $newID = $json->id;

        $this->assertNotEquals($originID,$newID);

        $json->facet_option_ids = $originFacetsIds;
        $json->workflow_id = Workflow::PUBLISHED;
        $arr = (array) $json;
        $r = $this->json('PUT',  "/api/1/main-object/$newID",$arr,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals($originID,$json->id);

    }


    public function testImport(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];
        $totalBefore = NetworkObject::where('network_id',1)->count();
        $r = $this->json('POST','/api/1/main-object/import',['main_object_ids' => [9]],$headers);
        $this->assertEquals(200,$r->response->getStatusCode());

        $totalAfter = NetworkObject::where('network_id',1)->count();

        $this->assertNotEquals($totalBefore,$totalAfter);

        NetworkObject::where('main_object_id',9)->where('network_id',1)->first()->delete();

    }

    public function testUpdateWithId3ForCheckCuratorID() {
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];

        $r = $this->json('GET',  '/api/1/main-object/3', [],$headers);

        $dataForUpdate = (array) (json_decode($r->response->getContent()));

        $r = $this->json('PUT', '/api/1/main-object/3', $dataForUpdate, $headers);
        $resultPUT = (json_decode($r->response->getContent()));

        $this->assertEquals(false, is_null($resultPUT->person_id_primary_curator));
    }


    public function testUpdateWithId3ForCheckSecundaryCurator() {
        $user = User::find(1);
        $token = "Bearer ".$user->newToken();
        $headers = ['HTTP_Authorization' => $token];

        $r = $this->json('GET',  '/api/1/main-object/3', [],$headers);

        $dataForUpdate = (array) (json_decode($r->response->getContent()));

        $r = $this->json('PUT', '/api/1/main-object/3', $dataForUpdate, $headers);
        $resultPUT = (json_decode($r->response->getContent()));

        $this->assertEquals(false, is_null($resultPUT->person_id_secundary_curator));
    }

    public function testUserInfo(){
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => $token->token];
        $r = $this->json('GET','/api/1/main-object-user-info?main_object_ids[]=1&main_object_ids[]=2',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Sebastião ',$json[0]->user_info->full_name);
        $this->assertEquals('Sebastião ',$json[1]->user_info->full_name);
        $this->assertEquals(1,$json[0]->main_object_id);
        $this->assertEquals(2,$json[1]->main_object_id);
    }

}
