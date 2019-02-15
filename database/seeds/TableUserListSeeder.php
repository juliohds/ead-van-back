<?php

use Illuminate\Database\Seeder;
use App\UserList;
use App\ItemList;

class TableUserListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                        
        $user_list = new UserList([
            'title' => 'Facere explicabo libero nihil culpa dicta quam omnis.',
            'description' => 'Facere explicabo libero nihil culpa dicta quam omnis.',
            'is_public' => true,
            'network_user_id' => 1
        ]);
        $user_list->save();

    }
}
