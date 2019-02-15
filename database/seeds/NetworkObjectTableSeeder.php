<?php

use Illuminate\Database\Seeder;
use App\NetworkObject;
use App\Network;
use App\MainObject;
use App\Workflow;

class NetworkObjectTableSeeder extends Seeder
{
    public function run()
    {
        $netA = Network::find(1);
        $netB = Network::find(2);
        $work = Workflow::find(Workflow::PUBLISHED);

        foreach(['oda','class_plan','course'] as $type){
            $cps = MainObject::where('oda_type','=',$type)->get();

            foreach($cps as $index => $cp){
                if($index > 2) {
                    break;
                }
                $no = new NetworkObject;
                $no->network_id = $netA->id;
                $no->main_object_id = $cp->id;    
                $no->workflow_id = $work->id;
                $no->save();
            }
        }

        $no = new NetworkObject;
        $no->network_id = $netB->id;
        $no->main_object_id = 1;    
        $no->workflow_id = $work->id;
        $no->save();

        $this->others();
        
    }
    //Create relation for objects that is not on network a
    //It brings possibility test imports;
    private function others(){
        $no = new NetworkObject;
        $no->network_id = 2;
        $no->main_object_id = 9;    
        $no->workflow_id = Workflow::PUBLISHED;
        $no->save();

        $no = new NetworkObject;
        $no->network_id = 2;
        $no->main_object_id = 18;    
        $no->workflow_id = Workflow::PUBLISHED;
        $no->save();
    }
}
