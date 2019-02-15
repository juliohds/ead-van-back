<?php

use App\Libs\TimeoutControl;

class TimeoutControlTest extends TestCase
{
    public function testSimpleIndex()
    {
        $start = new \DateTime("now");
        $tc = new TimeoutControl(3);
        while(!$tc->isTimeout()) {
            //Do nothing
        }
        $interval = (new \DateTime("now"))->diff($start);
        $this->assertTrue($interval->s >= 3);
    }    


}
