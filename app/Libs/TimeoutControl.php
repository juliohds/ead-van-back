<?php

namespace App\Libs;
class TimeoutControl {
    private $seconds = 0;
    private $start;
    public function __construct($seconds){
        $this->seconds = $seconds;
        $this->start = new \DateTime("now");
    }

    public function isTimeout(){
        $interval = (new \DateTime("now"))->diff($this->start);
        return  $interval->s > $this->seconds;
    }
}