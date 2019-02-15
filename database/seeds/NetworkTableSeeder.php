<?php

use Illuminate\Database\Seeder;
use App\Network;
use App\NetworkConfig;

class NetworkTableSeeder extends Seeder
{
    public function run()
    {

        $values = ['Rede A','Rede B','Rede C','Rede D'];

        foreach($values as $v){
            $e = new Network;
            $e->name = $v;
            $e->url = str_slug($v).'.com';
            if($v == 'Rede A'){
                $e->alternate_url = 'localhost';
            }
            $e->save();
            
        }

        $this->config();
    }

    private function config(){
        $nc = new NetworkConfig;
        $nc->use_revisor = false;
        $nc->save();
        $net = Network::find(2);
        $net->networkConfig()->associate($nc);
        
    }
}
