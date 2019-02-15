<?php

namespace App;

//DigitalResource groups class commons
abstract class DigitalResource extends Entity
{
    public function main()
    {
        return $this->morphOne('App\MainObject','oda');
    }
}
