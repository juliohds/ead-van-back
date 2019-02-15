<?php

use App\Libs\Elasticsearch;
use App\Libs\QueryOption;

class ElasticsearchTest extends TestCase
{

    public function testSimpleIndex()
    {
        $item = new Item;
        $item->id = 1;
        $item->name = 'Anderson Lira';
        $item->age = 35;
        $es = ElasticSearch::Instance();
        $es->indexItem($item);
        $response = $es->search("*lir*",Item::class);
        $this->assertEquals("Anderson Lira", $response->hits->hits[0]->_source->name);
        $queryOptions = [
            new QueryOption("terms","age",[34,35,36])
        ];
        $response = $es->search("*lir*",Item::class,$queryOptions);
        $this->assertEquals("Anderson Lira", $response->hits->hits[0]->_source->name);
    }

    public function testSimpleIndexNot()
    {
        $item = new Item;
        $item->id = 2;
        $item->name = 'Paulo Lopes';
        $item->age = 28;
        $es = ElasticSearch::Instance();
        $es->indexItem($item);
        $response = $es->search("*",Item::class);
        $this->assertEquals(2, $response->hits->total);
        $queryOptions = [
            new QueryOption("terms","age",[34,35,36],true)
        ];
        $response = $es->search("*",Item::class,$queryOptions);
        $this->assertEquals(1, $response->hits->total);
        $this->assertEquals("Paulo Lopes", $response->hits->hits[0]->_source->name);
    }    

    public function testIndexMany()
    {
        $json = '{ "index":{ "_index": "item", "_type": "search","_id":10 } }
{"id":"1", "name":"john doe","age":25 }
{ "index":{ "_index": "item", "_type": "search","_id":20 } }
{ "id":"2","name":"mary smith","age":32 }
{ "index":{ "_index": "item", "_type": "search","_id":30 } }
{ "id":"3","name":"Aifosnai Elehcim","age":32 }
';
        $fileName = "/tmp/jsonIndex-ABkk-y.json";
        writeFile($fileName,$json);
        $es = ElasticSearch::Instance();
        $es->indexMany($fileName);

        $response = $es->search("*h*",Item::class);
        $this->assertEquals(3, $response->hits->total);
        

    }    

    public function testNetworkIndex()
    {
        $item = new Item;
        $item->id = 1;
        $item->name = 'Anderson Lira';
        $item->age = 35;
        $es = ElasticSearch::Instance();
        $options = ["networkId" => 1];
        $es->indexItem($item,$options);
        $response = $es->search("*lir*",Item::class);
        $this->assertEquals("Anderson Lira", $response->hits->hits[0]->_source->name);
        $queryOptions = [
            new QueryOption("terms","age",[34,35,36])
        ];
        $response = $es->search("*lir*",Item::class,$queryOptions,$options);
        $this->assertEquals("Anderson Lira", $response->hits->hits[0]->_source->name);
    }

    public function testPaginate()
    {
        $options = [
            "from" => 0,
            "size" =>1
        ];
        $es = ElasticSearch::Instance();
        $response = $es->search("*h*",Item::class,null,$options);
        $this->assertEquals(1, count($response->hits->hits));
    }    
    
    public function testSort()
    {
        $options = [
            "sort" => ['age'=>'desc','name' => 'asc']
        ];
        $es = ElasticSearch::Instance();
        $response = $es->search("*",Item::class,null,$options);
        $this->assertEquals(5, count($response->hits->hits));
        $this->assertEquals("Anderson Lira", $response->hits->hits[0]->_source->name);
        $this->assertEquals("Aifosnai Elehcim", $response->hits->hits[1]->_source->name);
        $this->assertEquals("mary smith", $response->hits->hits[2]->_source->name);
    }

    public function testDeleteItem(){
        $item = new Item;
        $item->id = 1000;
        $item->name = 'Itemfordelete';
        $item->age = 65;
        $es = ElasticSearch::Instance();
        $options = ["networkId" => 1];
        $es->indexItem($item,$options);
        $response = $es->search("itemfordelete",Item::class,null,$options);
        $this->assertEquals("Itemfordelete", $response->hits->hits[0]->_source->name);
        $this->assertEquals(1, $response->hits->total);

        $es->deleteItem($item,$options);

        $response = $es->search("itemfordelete",Item::class,null,$options);
        $this->assertEquals(0, $response->hits->total);
    }    
    
    public function testDeleteAll(){
        $es = ElasticSearch::Instance();
        $response = $es->deleteAll();
        $this->assertEquals('"{\"acknowledged\":true}"',json_encode($response));
        //Call when delete items
        TestCase::indexRemoved();
    }    


}
class Item {
    public static $searchFields = [
        'name' => 'wildcard'
    ];
    public $id;
    public $name;
    public $age;

    public function getIndexable(){
        return json_encode($this);
    }
}