<?php

use Illuminate\Database\Seeder;
use App\TypeSchool;
class TypeSchoolTableSeeder extends Seeder
{
    public function run()
    {

        $types = ['Federal','Estadual','Municipal','Privada'];

        foreach($types as $type){
            $p = new TypeSchool;
            $p->title = $type;
            $p->save();
        }
    }
}
