<?php

use App\Console\Commands\IndexCommand;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function jsonResponse($method,$url,$body, $headers = ['Content-Type'=>'application/json'])
    {
        $r = $this->json($method,  $url, $body,$headers);
        $json = (json_decode($r->response->getContent()));
        $result = [
            'data' => $json,
            'status' => $r->response->getStatusCode()
        ];
        return $result;
    }

    private static $indexed = false;
    public static function index(){
        if(!TestCase::$indexed){
            TestCase::$indexed = true;
            $ic = new IndexCommand();
            $ic->handle();
        }
    } 

    public static function indexRemoved(){
        TestCase::$indexed = false;
    }
}
