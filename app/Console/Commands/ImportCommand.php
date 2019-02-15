<?php
/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;


use App\Network;
use App\TargetObject;
use App\MainObject;
use App\NetworkFacet;
use App\Facet;

use Exception;
use Illuminate\Console\Command;



/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class ImportCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "import:work";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Make import tasks after first import";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->facets();
        $this->classfication();        
    }

    private function facets(){
        try {
            $parent = Network::where('url','=','escoladigital.org.br')
                ->orWhere('url','=','escola.local.io')->first();
            $idNetwork = $parent->id;
            
            $facets = Facet::where('global','=',true)->get();
            $total = count($facets);
            foreach($facets as $index => $facet) {
                println(($index + 1)." de ".$total);
                println($facet->title);
                $nf = new NetworkFacet([
                    "network_id" => $idNetwork,
                    "facet_id" => $facet->id,
                    "ui_index" => $index,
                ]);
                $nf->save();
            }
        } catch (Exception $e) {
            $this->error($e);
        }

    }

    private function classfication(){
        try{
            $file = fopen('./database/classif.csv','r');
            while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
                if(strlen($data[1]) > 0){
                   $ids = explode(',',$data[1]);
                   $targetIds = TargetObject::whereIn('old_id',$ids)->pluck('id')->toArray();
                   
                   $odas = MainObject::whereIn('target_object_id',$targetIds)->get();

                   foreach($odas as $oda){
                        $oda->facetOptions()->syncWithoutDetaching([$data[0]]);
                        $oda->save();
                   }
                }
            } 
            fclose($file);
        }catch(\Exception $e){
            throw $e;
        }

    }

}
