<?php


class SearchControllerTest extends TestCase
{


    public function testNetworks()
    {
        TestCase::index();
        $r = $this->json('GET', '/api/1/search?q=Goo', []);
        $json = (json_decode($r->response->getContent()));

        $this->assertEquals(6, $json->hits->total);
             
    }

    public function testNetworksWithPagination()
    {
        $r = $this->json('GET', '/api/1/search?q=Goo&page=1&per_page=1', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(1, count($json->hits->hits));
        $this->assertEquals(6, $json->pages);
        $this->assertEquals(1, $json->current_page);
             
        $r = $this->json('GET', '/api/1/search?q=Goo&page=2&per_page=1', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(1, count($json->hits->hits));

        $r = $this->json('GET', '/api/1/search?q=Goo&page=3&per_page=1', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(1, count($json->hits->hits));

        $r = $this->json('GET', '/api/1/search?q=Goo&page=7&per_page=1', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(0, count($json->hits->hits));

        $r = $this->json('GET', '/api/1/search?q=Goo&page=1&per_page=2', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2, count($json->hits->hits));
        $this->assertEquals(3, $json->pages);

        $r = $this->json('GET', '/api/1/search?q=Goo&page=3&per_page=2', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2, count($json->hits->hits));
    }

    public function testNotFoundException()
    {
        $r = $this->json('GET', '/api/1000/search?q=Goo', []);

        $this->assertEquals(404,$r->response->getStatusCode());
             
    }

    public function testWithFacets()
    {
        $r = $this->json('GET', '/api/1/search?q=Goo&option_ids[]=1', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2, $json->hits->total);
        $this->assertEquals(2,$json->aggregations->facets->buckets[0]->doc_count);

        $r = $this->json('GET', '/api/1/search?q=Goo&option_ids[]=2', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(6, $json->hits->total);
        $this->assertEquals(6,$json->aggregations->facets->buckets[0]->doc_count);


        $r = $this->json('GET', '/api/1/search?q=Goo&option_ids[]=1&option_ids[]=2', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2, $json->hits->total);
        $this->assertEquals(2,$json->aggregations->facets->buckets[0]->doc_count);

        $r = $this->json('GET', '/api/1/search?q=Goo&option_ids[]=1&option_ids[]=3', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(0, $json->hits->total);

        $r = $this->json('GET', '/api/1/search?q=Google0&option_ids[]=2', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(2, $json->hits->total);
        $this->assertEquals(2,$json->aggregations->facets->buckets[0]->doc_count);

    }

    public function testSorting()
    {
        $r = $this->json('GET', '/api/1/search?q=Goo&sort=title&order=desc', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Google2', $json->hits->hits[0]->_source->title);
        $this->assertEquals('Google2', $json->hits->hits[1]->_source->title);
        $this->assertEquals('Google1', $json->hits->hits[2]->_source->title);
             
        $r = $this->json('GET', '/api/1/search?q=Goo&sort=title', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('Google0', $json->hits->hits[0]->_source->title);
        $this->assertEquals('Google0', $json->hits->hits[1]->_source->title);
        $this->assertEquals('Google1', $json->hits->hits[2]->_source->title);

    }

    public function testType()
    {
        $r = $this->json('GET', '/api/1/search?q=*&type=oda', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(3, $json->hits->total);
        $this->assertEquals(3,$json->aggregations->types->buckets[0]->doc_count);

        $r = $this->json('GET', '/api/1/search?q=*&type=class_plan', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(3, $json->hits->total);
        $this->assertEquals(3,$json->aggregations->types->buckets[0]->doc_count);
        
        $r = $this->json('GET', '/api/1/search?q=*&type=course&sort=title', []);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(3, $json->hits->total);
        $this->assertEquals('Course0', $json->hits->hits[0]->_source->title);

    }




}