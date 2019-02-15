<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Menu;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $items = [0,1,2,3,4,5,6,7];
        $title = ["home", "professores","gestores escolares", "colabore", "contato", "ajuda", "noticias", "sobre"]; 
        
        for ($i=0; $i < 8; $i++) { 
            $menu = new Menu;
            $menu->title = $title[$i];
            $menu->url = "www.google.com";
            $menu->blank = true;
            $menu->ui_index = $i;
            $menu->network_config_id = 2;
            $menu->save();
        }

    }
}
