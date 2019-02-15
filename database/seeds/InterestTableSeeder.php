<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Interest;
class InterestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        $interests = ['Arte','Biologia','Ciencia','Educação Fisica','Espanhol',
            'Filosofia','Física','Geografia','História','Ingles','Português',
            'Matemática','Quimica','Sociologia'];

        foreach($interests as $interest){
            $i= new Interest;
            $i->title = $interest;
            $i->save();
        }
    }
}
