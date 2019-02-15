<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Gender;
class GenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $genders = ['Masculino','Feminino','Outro'];

        foreach($genders as $gender){
            $e = new Gender;
            $e->title = $gender;
            $e->save();
        }
    }
}
