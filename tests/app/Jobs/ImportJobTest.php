<?php

use App\Jobs\ImportJob;
use App\NetworkObject;

class ImportJobTest extends TestCase
{
    public function testImport()
    {
        $totalBefore = count(NetworkObject::where('network_id',2)->get());
        $job = (new ImportJob(5,2));        
        dispatch($job);
        $totalAfter = count(NetworkObject::where('network_id',2)->get());
        
        $this->assertNotEquals($totalBefore,$totalAfter);
        NetworkObject::where('main_object_id',5)->where('network_id',2)->first()->delete();
    }    

}
