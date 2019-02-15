<?php

namespace App\Jobs;
use Log;
use App\Workflow;
use App\NetworkObject;

class ImportJob extends Job 
{

    protected $mainObjectId;
    protected $networkId;
    protected $workflowId;


    public function __construct($mainObjectId,$networkId,$workflowId = Workflow::PUBLISHED)
    {
        $this->mainObjectId = $mainObjectId;
        $this->networkId = $networkId;
        $this->workflowId = $workflowId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $no = new NetworkObject;
            $no->main_object_id = $this->mainObjectId;
            $no->network_id = $this->networkId;
            $no->workflow_id = $this->workflowId;
            Log::warning('Importing main_object_id '.$this->mainObjectId
                .' for network_id '.$this->networkId);
            
            $no->save();
            //Update others references on elastic search
            $others = NetworkObject::where('main_object_id',$this->mainObjectId)->get();
            foreach($others as $other){
                $other->save();
            }
        }catch(\Exception $e){
            Log::warning($e);

        }
    }
}