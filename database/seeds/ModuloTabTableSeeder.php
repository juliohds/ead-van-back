<?php

use Illuminate\Database\Seeder;

use App\ModuloTab;

class ModuloTabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ModuloTab = new ModuloTab;
        $ModuloTab->title = "ModuloTab Title";
        $ModuloTab->network_id = 1;
        $ModuloTab->save();
        
    }
}
