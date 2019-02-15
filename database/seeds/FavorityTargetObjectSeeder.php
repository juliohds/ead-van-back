<?php

use Illuminate\Database\Seeder;
use App\FavorityTargetObject;

class FavorityTargetObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    
    {

        $e = new FavorityTargetObject;
        $e->picked = true;
        $e->network_user_id = 1;
        $e->target_object_id = 1;
        $e->save();
    
    }
}
