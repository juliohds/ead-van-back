<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;


use App\MainObject;
use App\NetworkObject;
use App\Workflow;
use App\Libs\Elasticsearch;

use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;



/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class IndexCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "index:all {--network=?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Index all indexable objects";


    protected function getOptions()
    {
        return [
            ['network', null, InputOption::VALUE_OPTIONAL, 'Id from network', null],
        ];


    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $networkID = $this->option('network');
        try {
            $indexCreated = [];
            $networkObjects = null;
            if(!is_numeric($networkID)){
                $networkObjects = NetworkObject::where('workflow_id','<>',Workflow::ARCHIVED)->get();
                $networkID = null;
            }else{
                $networkObjects = NetworkObject::where('network_id',$networkID)
                    ->where('workflow_id','<>',Workflow::ARCHIVED)->get();
            }
            $total = count($networkObjects);
            $es =  Elasticsearch::Instance();
            $es->deleteAll($networkID);
            $i = 0;
            $all = "";
            $fileName = $networkID ? "/tmp/indexOdas$networkID.json" : "/tmp/indexOdas.json";
            foreach($networkObjects as $no){
                $i++;
                if($i % 1000 == 0){
                    println($i." de ".$total);
                    writeFile($fileName,$all);
                    ($es->indexMany($fileName));
                    $all = "";
                }
                if(!array_key_exists("key-".$no->network_id,$indexCreated)){
                    $es->createIndexWithMapping($no->getMapping(), $no->network_id, NetworkObject::class);
                    $indexCreated["key-".$no->network_id] = true;
                }

                $all = $all.$this->jsonHeader($no->network_id,$no->id);
                $all = $all.$no->getIndexable()."\n";
            }

            if($all != ""){
                println($i." de ".$total);
                writeFile($fileName,$all);
                ($es->indexMany($fileName));
            }
            println("Finished ".($networkID ? "network $networkID":"all"));
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }

    private function jsonHeader($idNetwork,$idObject){
        return  '{ "index":{ "_index": "'.$idNetwork.'", "_type": "appnetworkobject","_id":'.$idObject.' } }'."\n";

    }
}
