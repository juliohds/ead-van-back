<?php

namespace App\Libs;

class Elasticsearch {

    private $host = "localhost";
    private $port = "9200";

    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Elasticsearch();
        }
        return $inst;
    }

    private function __construct()
    {
        $this->host = getenv('ELASTICSEARCH_HOST') ? getenv('ELASTICSEARCH_HOST') :'localhost';
        $this->port = getenv('ELASTICSEARCH_PORT') ? getenv('ELASTICSEARCH_PORT') :'9200';
    }

    public function deleteItem($item,$options = null){
        $req = curl_init();
        $url = $this->buildUrl(get_class($item),$options);
        $url = $url.$item->id."?refresh=true";
        curl_setopt_array($req, [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($req);
        return $response;
    }
    public function deleteAll($index = null){
        $req = curl_init();
        $url = "http://$this->host:$this->port/_all";
        if($index){
            $url = "http://$this->host:$this->port/$index";
        }

        curl_setopt_array($req, [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($req);
        return $response;
    }


    public function indexMany($fileName){
        $req = curl_init();
        $url = "http://$this->host:$this->port/_bulk?refresh=true";

        curl_setopt_array($req, [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => file_get_contents($fileName),
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($req);
        return $response;
    }

    public function indexItem($item,$options = []){
        $req = curl_init();

        $url = $this->buildUrl(get_class($item),$options);

        curl_setopt_array($req, [
            CURLOPT_URL            => $url.$item->id."?refresh=true",
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLOPT_POSTFIELDS     => $item->getIndexable(),
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
            CURLOPT_RETURNTRANSFER => true,
        ]);


       
        $response = curl_exec($req);
        return $response;
    }

    public function createIndexWithMapping(array $indexConfig, $indexName, $docGroupName) {
        $req = curl_init();

        $url = "http://$this->host:$this->port/$indexName";

        $newDocGroupName = strtolower("".$docGroupName);
        $newDocGroupName = str_replace("\\","",$newDocGroupName);

        $postFields = '{
          "mappings": {
            "'.$newDocGroupName.'": {
              "properties": '
                  .json_encode($indexConfig).
              '
            }
          }
        }';

        curl_setopt_array($req, [
            CURLOPT_URL            => $url,
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
        ]);
        return curl_exec($req);
    }

    public function search($queryString,$class,$queryOptions = null,$options = []){
        $req = curl_init();

        $query = $this->buildQuery($queryString, $class,$queryOptions,$options);

        $url = $this->buildUrl($class,$options);
        curl_setopt_array($req, [
            CURLOPT_URL            => $url."_search?pretty",
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($query),
            CURLOPT_HTTPHEADER     => [ "Content-Type" => "application/json" ],
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($req);
        return json_decode($response);
    }

    private function buildUrl($class, $options = null){
        $networkId = null;
        if(array_key_exists("networkId",$options)){
            $networkId = $options["networkId"];
        }
        $indexName =  strtolower("".$class);
        $indexName = str_replace("\\","",$indexName);
        if($networkId){
            return "http://$this->host:$this->port/$networkId/$indexName/";
        }
        return "http://$this->host:$this->port/$indexName/search/";
    }

    private function buildQuery($queryString, $class,$queryOptions = null,$options = []){

        $query = [
            "aggs" => [
                "facets" => [
                    "terms" => [
                        "field" => "facet_option_ids",
                        "size" => 3000,

                    ]
                ],
                "types" => [
                    "terms" => [
                        "field" => "oda_type",
                        "size" => 10,

                    ]
                ], 
                "bncc" => [
                    "terms" => [
                        "field" => "bncc_tags_array",
                        "size" => 2000,

                    ]
                ]
            ],
            "query" => [
                "bool" => [
                    "must" => [
                        [
                            "bool" => [
                                "should" => [],
                            ]
                        ]

                    ],
                    "must_not" => [

                    ],
                    "should" =>[]
                ]
            ]
        ];

        if(array_key_exists("from",$options) && array_key_exists("size",$options)){
            $query["from"] = $options["from"];
            $query["size"] = $options["size"];
        }
        if(array_key_exists("sort",$options)){
            $sort = $options['sort'];
            $query['sort'] = [];
            foreach($sort as $k => $v){
                array_push($query['sort'],[$k => $v]);
            }
        }


        foreach($class::$searchFields as $key => $value) {
            $match = [
                $value => [
                    $key => ($value == 'wildcard' ? "*".$queryString."*" : $queryString)
                ]
            ];

            array_push($query["query"]["bool"]["must"][0]["bool"]["should"],$match);
        }

        foreach((array)$queryOptions as $qo) {
            $match = [
                $qo->type => [
                    $qo->key => $qo->value
                ]
            ];
            $key = $qo->not ? "must_not" : "must";
            // if($qo->or){
            //     array_push($query["query"]["bool"]["should"],$match);
            // }else{
                array_push($query["query"]["bool"][$key],$match);
            // }
        }

        if(array_key_exists("workflow_id",$options)){
            $match = [
                'match' => [
                    'workflow_id' => $options['workflow_id']
                ]
            ];
            array_push($query["query"]["bool"]["must"],$match);
        }
        return $query;

    }

}
