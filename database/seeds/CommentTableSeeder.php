<?php

use Illuminate\Database\Seeder;
use App\Comment;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comment = new Comment;

        $text = "Tenetur sint voluptas quis dignissimos repudiandae neque architecto.";

        $numbers_id = [[1]];

        foreach($numbers_id as $id_number){
            foreach($id_number as $id){
                $c = new Comment;
                $c->text = $text;
                $c->network_user_id = 1;
                $c->target_object_id = 1;
                $c->save();
            }
    
        }
    }
}
