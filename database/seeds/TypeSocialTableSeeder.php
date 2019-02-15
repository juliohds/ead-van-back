<?php

use Illuminate\Database\Seeder;
use App\TypeSocial;
class TypeSocialTableSeeder extends Seeder
{
    public function run()
    {

        $values = [TypeSocial::FACEBOOK,TypeSocial::TWITTER,TypeSocial::GOOGLE_PLUS,
            TypeSocial::YOUTUBE];
        foreach($values as $value){
            $e = new TypeSocial;
            $e->id = $value;
            $e->type = TypeSocial::defaultValue($value);
            $e->title =  ucwords(TypeSocial::defaultValue($value));
            $e->save();
        }
    }
}
