<?php

use Illuminate\Database\Seeder;
use App\Card;

class CardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Card = new Card;
        $Card->title = "Card Seeder";
        $Card->description = "Card Seeder";
        $Card->url = "Card Seeder";
        $Card->img = "Card Seeder";
        $Card->tab_id = 1;
        $Card->save();
        
    }
}


