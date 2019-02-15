<?php

use Illuminate\Database\Seeder;
use App\Tab;

class TabTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Tab = new Tab;
        $Tab->title = "Tab Title";
        $Tab->modulo_tab_id = 1;
        $Tab->save();
    }
}
