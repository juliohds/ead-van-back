<?php

use App\Facet;
use App\NetworkFacet;
use App\FacetOption;
use App\NetworkFacetOption;
class FacetOptionControllerTest extends TestCase
{

    public function testUpdateFacetOption(){
        $token = AuthControllerTest::getToken($this);
        $headers = ['Authorization' => 'Bearer '.$token->token];

        //Saving option with other name
        $obj = [
            'title' => 'Ciências da Natureza',
            'enabled' => true,
            'ui_index' => -1,
            'picture' => 'http://fakeisfake0001.com/3.png',

        ];

        $r = $this->jsonResponse('PUT',  '/api/1/facets/1/options/1', $obj,$headers);
        $json = $r['data'];

        //Check if list is updated and order is same
        $this->assertEquals(200,$r['status']);
        $this->assertEquals('Ciências da Natureza',$json->network_title);
        $this->assertEquals(-1,$json->ui_index);
        $this->assertEquals('http://fakeisfake0001.com/3.png',$json->network_picture);
        $this->assertTrue($json->enabled);

        $all = $this->jsonResponse('GET', '/api/1/facets', [])['data'];
        $json = $all[0];
        $this->assertEquals('Ciências da Natureza', $json->options[0]->network_title);
        
        //Saving option with old name to preserve app state
        $obj = [
            'title' => 'Ciências',
            'enabled' => false,
            'ui_index' => 0,
            'picture' => '',
        ];

        $r = $this->jsonResponse('PUT',  '/api/1/facets/1/options/1', $obj,$headers);
        $json = $r['data'];
        $all = $this->jsonResponse('GET', '/api/1/facets', [])['data'];
        $json = $all[0];
        $this->assertEquals('Ciências', $json->options[0]->network_title);

        
        
    }      
}