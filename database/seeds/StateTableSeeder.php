<?php

use Illuminate\Database\Seeder;
use App\State;

class StateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('state')->delete();
        $file = fopen("database/estadosSeed.csv","r");

            while(! feof($file))
            {
                $estadoCsv = fgetcsv($file);
                println($estadoCsv[1]);
                $estado = new State;
                $estado->name = $estadoCsv[1];
                $estado->uf = $estadoCsv[2];
                $estado->ibge_uf = $estadoCsv[0];
                $estado->save();
            }

            fclose($file);
        
    }
}
