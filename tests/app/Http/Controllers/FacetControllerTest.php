<?php

use App\Facet;
use App\NetworkFacet;
use App\FacetOption;
use App\NetworkFacetOption;
class FacetControllerTest extends TestCase
{


    public function testNetworkFacets()
    {

        $all = $this->jsonResponse('GET', '/api/1/facets', [])['data'];
        $this->assertEquals(3,count($all));
        $json = $all[0];
        $this->assertEquals(1, $json->id);
        $this->assertEquals(1, $json->ui_index);
        $this->assertEquals('Disciplina', $json->network_title);
        
        $this->assertEquals(3,count($json->options));
        $option1 = $json->options[0];
        $option2 = $json->options[1];
        $option3 = $json->options[2];

        $this->assertEquals('Ciências',$option1->network_title);
        $this->assertEquals('Matemática',$option2->network_title);
        $this->assertEquals('Lingua Portuguesa',$option3->network_title);
             
    }

    public function testNetworkFacetsByType()
    {

        $all = $this->jsonResponse('GET', '/api/1/facets?facet_type=course', [])['data'];
        $this->assertEquals(1,count($all));
        $json = $all[0];
        $this->assertEquals(4, $json->id);
        $this->assertEquals(4, $json->ui_index);
        $this->assertEquals('Oferecido Por', $json->network_title);
        
        $this->assertEquals(3,count($json->options));
        $option1 = $json->options[0];
        $option2 = $json->options[1];
        $option3 = $json->options[2];

        $this->assertEquals('Joana',$option1->network_title);
        $this->assertEquals('Joeloy',$option2->network_title);
        $this->assertEquals('Xilico',$option3->network_title);
             
    }


    public function testAdminMiddlware(){
        $token = AuthControllerTest::getToken($this,2);

        $obj = ['v'=>'v'];
        $headers = ['Authorization' => 'Bearer '.$token->token];
        $r = $this->json('POST',  '/api/1/facets', $obj,$headers);
        
        $this->assertEquals(403,$r->response->getStatusCode());

        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];

        $r = $this->json('POST',  '/api/1/facets', $obj,$headers);
        
        $this->assertEquals(422,$r->response->getStatusCode());
        
    }

    public function testCreateFacet(){
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];

        $obj = [
            'facet_type' => 'course',
            'title' => 'Course Facet',
            'help_text' => 'This is a test facet',
            'suggestion_enabled' => true,
            'required' => true,
            'enabled' => true

        ];

        $r = $this->json('POST',  '/api/1/facets', $obj,$headers);
        $json = (json_decode($r->response->getContent()));

        $this->assertEquals(201,$r->response->getStatusCode());
        $this->assertEquals('course',$json->facet_type);
        $this->assertEquals('Course Facet',$json->network_title);

        NetworkFacet::find($json->id)->delete();
        Facet::find($json->facet_id)->delete();
        
    }
    public function testCreateOption(){
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];

        $obj = [
            'facet_type' => 'course',
            'title' => 'Course Facet',
            'help_text' => 'This is a test facet',
            'suggestion_enabled' => true,
            'required' => true,
            'enabled' => true

        ];

        $r = $this->json('POST',  '/api/1/facets', $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        $facetId = $json->facet_id;
        $networkFacetId = $json->id;

        $obj = [
            'title' => 'Course Option',
            'picture' => 'http://escoladigital-prod.s3.amazonaws.com/',
            'enabled' => true
        ];
        

        $r = $this->json('POST',  "/api/1/facets/$facetId/options", $obj,$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertTrue($json->enabled);
        $this->assertEquals('Course Option',$json->network_title);
        $this->assertEquals('http://escoladigital-prod.s3.amazonaws.com/',$json->network_picture);

        NetworkFacetOption::find($json->id)->delete();
        FacetOption::find($json->facet_option_id)->delete();
        

        NetworkFacet::find($networkFacetId)->delete();
        Facet::find($facetId)->delete();
    }

    public function testShowUnrelatedFacetOption(){
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];


        $obj = [
            'title' => 'New Discipline Facet',
            'picture' => 'http://escoladigital-prod.s3.amazonaws.com/',
            'enabled' => true
        ];
        

        //Facetoption created on network 1
        $json = $this->jsonResponse('POST',  "/api/1/facets/1/options", $obj,$headers)['data'];
        $id = $json->id;
        $facetOptionId = $json->facet_option_id;

        //On network 2, previous facetoption musth show as not related
        $json = $this->jsonResponse('GET', '/api/2/facets', [])['data'][0];
        $this->assertEquals('Disciplina', $json->network_title);
        $this->assertEquals(3, count($json->options));
        $this->assertEquals(1, count($json->unrelated_options));
        $this->assertEquals('New Discipline Facet', $json->unrelated_options[0]->title);

        NetworkFacetOption::find($id)->delete();
        FacetOption::find($facetOptionId)->delete();
        

    }
    


    public function testUpdateFacet(){
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];

        //Saving discipline with other name
        $obj = [
            'title' => 'Matéria',
            'suggestion_enabled' => true,
            'required' => true,
            'enabled' => true,
            'ui_index' => -1,
            'limit' => 10,

        ];

        $r = $this->jsonResponse('PUT',  '/api/1/facets/1', $obj,$headers);
        $json = $r['data'];

        //Check if list is updated and order is same
        $this->assertEquals(200,$r['status']);
        $this->assertEquals('Matéria',$json->network_title);
        $this->assertEquals(-1,$json->ui_index);
        $this->assertEquals(10,$json->limit);
        $this->assertEquals('Matéria',$json->network_title);
        $this->assertTrue($json->suggestion_enabled);
        $this->assertTrue($json->required);
        $this->assertTrue($json->enabled);

        $all = $this->jsonResponse('GET', '/api/1/facets', [])['data'];
        $this->assertEquals(3,count($all));
        $json = $all[0];
        $this->assertEquals('Matéria', $json->network_title);
        
        //Saving discipline with old name to preserve app state
        $obj = [
            'title' => 'Disciplina',
            'suggestion_enabled' => false,
            'required' => false,
            'enabled' => false,
            'ui_index' => 0,
            'limit' => 0

        ];

        $r = $this->jsonResponse('PUT',  '/api/1/facets/1', $obj,$headers);
        $json = $r['data'];
        $all = $this->jsonResponse('GET', '/api/1/facets', [])['data'];
        $this->assertEquals(3,count($all));
        $json = $all[0];
        $this->assertEquals('Disciplina', $json->network_title);

        
        
    }    
    
}