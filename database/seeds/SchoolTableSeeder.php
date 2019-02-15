<?php

use Illuminate\Database\Seeder;
use App\School;
use App\TypeSchool;
use App\City;
class SchoolTableSeeder extends Seeder
{
    public function run()
    {
        $file = fopen("database/escolasSeed.csv","r");

        while(! feof($file))
        {
            $schoolCsv = fgetcsv($file);
            println($schoolCsv[1]);
            $city =  City::where('ibge_municipio','=',$schoolCsv[2])->first();
            $school = new School;
            $school->name = $schoolCsv[1];
            $school->inep = $schoolCsv[0];
            $school->city_id = $city->id;
            $school->type_id = $schoolCsv[3];
            $school->save();
        }

        fclose($file);
        // $s = new School;
        // $s->name = 'ESCOLA ADVENTISTA PIRAJUCARA';
        // $s->inep = '35583443';

        // $city =  City::where('name','=','Embu das Artes')->first();
        // $s->city()->associate($city);

        // $type = TypeSchool::where('title','=','Privada')->first();
        // $s->type()->associate($type);

        // $s->save();

    }
}
