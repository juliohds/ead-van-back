<?php

use Illuminate\Database\Seeder;
use App\FacetType;
class FacetTypeTableSeeder extends Seeder
{
    public function run()
    {

        $values = [FacetType::ODA,FacetType::COURSE];
        foreach($values as $value){
            $e = new FacetType;
            $e->id = $value;
            $e->tag = FacetType::defaultValue($value);
            $e->title =  translate(FacetType::defaultValue($value));
            $e->save();
        }
    }
}
