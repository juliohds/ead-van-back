<?php

use Illuminate\Database\Seeder;
use App\ListSlug;
use App\Helpers\SlugHelper;

class TableListSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ls = new ListSlug;
        $sh = new SlugHelper;
        $ls->id = 1;
        $ls->url = $sh->urlAmigavel("Lingua Portuguesa - 2º ano/1º Bimestre"." id ".$ls->id);
        $ls->user_list_id = 1;
        $ls->save();
    }

    
}
