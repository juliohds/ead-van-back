<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Grade;
class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        
        $grades = ['Educação Infantil','1o. ano do Ensino Fundamental',
            '2o. ano do Ensino Fundamental','3o. ano do Ensino Fundamental',
            '4o. ano do Ensino Fundamental','5o. ano do Ensino Fundamental',
            '6o. ano do Ensino Fundamental','7o. ano do Ensino Fundamental',
            '8o. ano do Ensino Fundamental','9o. ano do Ensino Fundamental',
            '1a série do Ensino Médio','2a série do Ensino Médio',
            '3a série do Ensino Médio','Ensino Superior','Pós Graduação',
            'Educação de Jovens e Adultos','Não se aplica'];

        foreach($grades as $grade){
            $g= new Grade;
            $g->title = $grade;
            $g->save();
        }
    }
}
