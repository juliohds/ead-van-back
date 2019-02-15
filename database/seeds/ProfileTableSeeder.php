<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Profile;
class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $profiles = [
            ['id'=>Profile::STUDENT,'title'=>'Aluno'],
            ['id'=>Profile::TEACHER,'title'=>'Professor'],
            ['id'=>Profile::COORDINATOR,'title'=>'Coordenador Pedagógico'],
            ['id'=>Profile::MANAGER,'title'=>'Gestor Escolar'],
            ['id'=>Profile::PARENT,'title'=>'Pai de Aluno'],
            ['id'=>Profile::OTHER,'title'=>'Outro']
        ];

        foreach($profiles as $profile){
            $p = new Profile;
            $p->id = $profile['id'];
            $p->tag = Profile::defaultValue($profile['id']);
            $p->title = $profile['title'];
            $p->save();
        }
    }
}
