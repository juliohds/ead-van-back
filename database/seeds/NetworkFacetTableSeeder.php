<?php

use Illuminate\Database\Seeder;
use App\Facet;
use App\NetworkFacet;
use App\Network;
use App\NetworkConfig;
use App\NetworkFacetOption;

class NetworkFacetTableSeeder extends Seeder
{
    public function run()
    {
        //Network seeders is creating 4 networks and facets.
        //Code below create its relationship
        $totalFacets = count(Facet::all());
        for($i = 1;$i <= 4;$i++){
            for($j = 1;$j <= $totalFacets;$j++){
                $nf = new NetworkFacet([
                    "network_id" => $i,
                    "facet_id" => $j,
                    "ui_index" => $j,
                ]);
                $nf->save();
            }        
        }
        
        $nc = new NetworkConfig;
        $nc->use_revisor = true;
        $nc->save();
        $nc->homeFacets()->sync([1,2,3]);
        $net = Network::find(1);
        $net->networkConfig()->associate($nc);
        $net->save();
        

        $nof = NetworkFacetOption::where('facet_option_id',1)->first();
        $nof->title = 'Lingua Portuguesa';
        $nof->save();
    }
}
