<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\CycleBncc;
class CycleBnccTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $p = new CycleBncc;
        $p->id = CycleBncc::EI;
        $p->tag = "EI";
        $p->title = CycleBncc::defaultValue(CycleBncc::EI);
        $p->save();

        $p = new CycleBncc;
        $p->id = CycleBncc::EF;
        $p->tag = "EF";
        $p->title = CycleBncc::defaultValue(CycleBncc::EF);
        $p->save();

        $p = new CycleBncc;
        $p->id = CycleBncc::EM;
        $p->tag = "EM";
        $p->title = CycleBncc::defaultValue(CycleBncc::EM);
        $p->save();

    }
}
